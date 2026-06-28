<?php

namespace App\Services\Payments\Drivers;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PagBankDriver extends AbstractGatewayDriver
{
    public function name(): string { return 'pagbank'; }

    public function supportedMethods(): array { return ['pix', 'credit_card']; }

    public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array
    {
        [$baseUrl, $apiKey] = $this->credentials();
        $reference = $this->reference($support);

        if ($method === 'credit_card') {
            return $this->creditCard($support, $baseUrl, $apiKey, $reference, $request);
        }

        return $this->pix($support, $baseUrl, $apiKey, $reference);
    }

    private function pix(ProjectSupportRequest $support, string $baseUrl, string $apiKey, string $reference): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post($baseUrl . '/orders', [
            'reference_id'     => $reference,
            'customer'         => [
                'name'         => $support->name,
                'email'        => $support->email ?: Setting::get('donation_default_payer_email', 'doacao@issm.org.br'),
                'tax_id'       => $support->document ? preg_replace('/\D/', '', $support->document) : '00000000000',
            ],
            'items'            => [[
                'name'         => 'Doacao para ' . optional($support->project)->title,
                'quantity'     => 1,
                'unit_amount'  => (int) round((float) $support->amount * 100),
            ]],
            'qr_codes'         => [[
                'amount'       => ['value' => (int) round((float) $support->amount * 100)],
                'expiration_date' => now()->addHours(24)->toIso8601String(),
            ]],
            'notification_urls'=> [route('payments.webhook', ['gateway' => 'pagbank'])],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('PagBank recusou a cobranca PIX: ' . $response->body());
        }

        $payload   = $response->json();
        $qrCodes   = data_get($payload, 'qr_codes.0', []);
        $qrText    = data_get($qrCodes, 'text');
        $qrImg     = data_get($qrCodes, 'links.0.href');
        $orderId   = data_get($payload, 'id');

        return $this->persist($support, [
            'gateway'     => 'pagbank',
            'method'      => 'pix',
            'status'      => $this->normalizeStatus((string) data_get($payload, 'status')) ?: 'pending',
            'external_id' => $orderId ? (string) $orderId : null,
            'reference'   => $reference,
            'payload'     => $payload,
            'public'      => [
                'type'       => 'pix',
                'qr_code'    => $qrText,
                'ticket_url' => $qrImg,
                'message'    => 'Escaneie o QR Code PIX ou copie o codigo para pagar.',
            ],
        ]);
    }

    private function creditCard(ProjectSupportRequest $support, string $baseUrl, string $apiKey, string $reference, Request $request): array
    {
        $encryptedCard = $request->input('encrypted_card');

        if (! $encryptedCard) {
            return $this->persist($support, [
                'gateway'   => 'pagbank',
                'method'    => 'credit_card',
                'status'    => 'requires_token',
                'reference' => $reference,
                'payload'   => ['message' => 'Aguardando criptografia do cartao via SDK PagBank.'],
                'public'    => [
                    'type'       => 'card_token_required',
                    'gateway'    => 'pagbank',
                    'public_key' => Setting::get('donation_pagbank_public_key', ''),
                    'support_id' => $support->id,
                    'message'    => 'Informe os dados do cartao para concluir a doacao.',
                ],
            ]);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post($baseUrl . '/orders', [
            'reference_id'  => $reference,
            'customer'      => [
                'name'      => $support->name,
                'email'     => $support->email ?: Setting::get('donation_default_payer_email', 'doacao@issm.org.br'),
                'tax_id'    => $support->document ? preg_replace('/\D/', '', $support->document) : '00000000000',
            ],
            'items'         => [[
                'name'      => 'Doacao para ' . optional($support->project)->title,
                'quantity'  => 1,
                'unit_amount' => (int) round((float) $support->amount * 100),
            ]],
            'charges'       => [[
                'reference_id' => $reference,
                'description'  => 'Doacao ISSM',
                'amount'       => ['value' => (int) round((float) $support->amount * 100), 'currency' => 'BRL'],
                'payment_method' => [
                    'type'         => 'CREDIT_CARD',
                    'installments' => max(1, (int) $request->input('installments', 1)),
                    'capture'      => true,
                    'card'         => ['encrypted' => $encryptedCard, 'security_code' => $request->input('cvv')],
                ],
            ]],
            'notification_urls' => [route('payments.webhook', ['gateway' => 'pagbank'])],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('PagBank recusou o pagamento com cartao: ' . $response->body());
        }

        $payload = $response->json();
        $charge  = data_get($payload, 'charges.0', []);
        $status  = $this->normalizeStatus((string) data_get($charge, 'status')) ?: 'pending';

        return $this->persist($support, [
            'gateway'     => 'pagbank',
            'method'      => 'credit_card',
            'status'      => $status,
            'external_id' => data_get($payload, 'id') ? (string) data_get($payload, 'id') : null,
            'reference'   => $reference,
            'payload'     => $payload,
            'public'      => [
                'type'    => $status === 'paid' ? 'paid' : 'pending',
                'message' => $status === 'paid' ? 'Pagamento aprovado.' : 'Pagamento em processamento.',
            ],
        ]);
    }

    public function handleWebhook(Request $request): array
    {
        $event     = $request->input('type', '');
        $charge    = $request->input('data.charges.0', []);
        $order     = $request->input('data', []);

        $externalId = data_get($charge, 'id') ?: data_get($order, 'id');
        $reference  = data_get($charge, 'reference_id') ?: data_get($order, 'reference_id');
        $rawStatus  = data_get($charge, 'status') ?: data_get($order, 'status', '');

        return [
            'external_id' => $externalId ? (string) $externalId : null,
            'reference'   => $reference,
            'status'      => $this->normalizeStatus((string) $rawStatus),
            'payload'     => $request->all(),
        ];
    }

    private function credentials(): array
    {
        $mode    = Setting::get('donation_pagbank_mode', 'production');
        $baseUrl = trim((string) Setting::get(
            $mode === 'sandbox' ? 'donation_pagbank_base_url_sandbox' : 'donation_pagbank_base_url',
            $mode === 'sandbox' ? 'https://sandbox.api.pagseguro.com' : 'https://api.pagseguro.com'
        ));
        $apiKey  = trim((string) Setting::get(
            $mode === 'sandbox' ? 'donation_pagbank_api_key_sandbox' : 'donation_pagbank_api_key', ''
        ));

        if (! $apiKey) {
            throw new RuntimeException('Configure a API Key do PagBank no painel administrativo.');
        }

        return [rtrim($baseUrl, '/'), $apiKey];
    }
}
