<?php

namespace App\Services\Payments\Drivers;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CoraDriver extends AbstractGatewayDriver
{
    public function name(): string { return 'cora'; }

    public function supportedMethods(): array { return ['pix']; }

    public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array
    {
        [$baseUrl, $apiKey] = $this->credentials();
        $reference = $this->reference($support);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post($baseUrl . '/v2/invoices', [
            'code'        => $reference,
            'services'    => [[
                'name'      => 'Doacao para ' . optional($support->project)->title,
                'amount'    => (int) round((float) $support->amount * 100),
                'quantity'  => 1,
            ]],
            'customer'    => [
                'name'      => $support->name,
                'email'     => $support->email ?: null,
                'document'  => $support->document ? ['number' => preg_replace('/\D/', '', $support->document), 'type' => 'CPF'] : null,
            ],
            'payment_terms' => [[
                'due_date'  => now()->addDays(1)->format('Y-m-d'),
                'amount'    => (int) round((float) $support->amount * 100),
            ]],
            'notifications' => ['webhook_url' => route('payments.webhook', ['gateway' => 'cora'])],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Cora recusou a cobranca: ' . $response->body());
        }

        $payload    = $response->json();
        $invoiceId  = data_get($payload, 'id');
        $pixPayload = data_get($payload, 'payment.pix.emv');
        $pixQr      = data_get($payload, 'payment.pix.qr_code_image_url');
        $invoiceUrl = data_get($payload, 'url');

        return $this->persist($support, [
            'gateway'     => 'cora',
            'method'      => 'pix',
            'status'      => 'pending',
            'external_id' => $invoiceId ? (string) $invoiceId : null,
            'reference'   => $reference,
            'payment_url' => $invoiceUrl,
            'payload'     => $payload,
            'public'      => [
                'type'       => 'pix',
                'qr_code'    => $pixPayload,
                'ticket_url' => $invoiceUrl,
                'message'    => 'Use o codigo PIX acima ou acesse o link para pagar.',
            ],
        ]);
    }

    public function handleWebhook(Request $request): array
    {
        $event      = $request->input('event_type', '');
        $invoiceId  = $request->input('invoice.id') ?: $request->input('id');
        $reference  = $request->input('invoice.code') ?: $request->input('code');

        $statusMap = [
            'INVOICE.PAID'      => 'paid',
            'INVOICE.CANCELED'  => 'failed',
            'INVOICE.OVERDUE'   => 'failed',
        ];

        return [
            'external_id' => $invoiceId ? (string) $invoiceId : null,
            'reference'   => $reference,
            'status'      => $statusMap[$event] ?? $this->normalizeStatus($event),
            'payload'     => $request->all(),
        ];
    }

    private function credentials(): array
    {
        $mode    = Setting::get('donation_cora_mode', 'production');
        $baseUrl = $mode === 'sandbox'
            ? 'https://api.sandbox.cora.com.br'
            : 'https://api.cora.com.br';
        $apiKey  = trim((string) Setting::get(
            $mode === 'sandbox' ? 'donation_cora_api_key_sandbox' : 'donation_cora_api_key', ''
        ));

        if (! $apiKey) {
            throw new RuntimeException('Configure a API Key da Cora no painel administrativo.');
        }

        return [$baseUrl, $apiKey];
    }
}
