<?php

namespace App\Services\Payments\Drivers;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AsaasDriver extends AbstractGatewayDriver
{
    public function name(): string { return 'asaas'; }

    public function supportedMethods(): array { return ['pix', 'credit_card']; }

    public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array
    {
        [$baseUrl, $apiKey] = $this->credentials();
        $reference = $this->reference($support);

        // Criar ou obter cliente no Asaas
        $customerId = $this->ensureCustomer($baseUrl, $apiKey, $support);

        $billingType = match ($method) {
            'credit_card' => 'CREDIT_CARD',
            default       => 'PIX',
        };

        $response = Http::withHeaders(['access_token' => $apiKey])
            ->post($baseUrl . '/v3/payments', [
                'customer'         => $customerId,
                'billingType'      => $billingType,
                'value'            => (float) $support->amount,
                'dueDate'          => now()->addDays(1)->format('Y-m-d'),
                'description'      => 'Doacao para ' . optional($support->project)->title,
                'externalReference'=> $reference,
                'notificationDisabled' => true,
                'postalService'    => false,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Asaas recusou a cobranca: ' . $response->body());
        }

        $payload = $response->json();
        $payId   = data_get($payload, 'id');

        // Para PIX, buscar QR code
        $public = ['type' => 'pending', 'message' => 'Cobranca criada no Asaas.'];

        if ($billingType === 'PIX' && $payId) {
            $pixResponse = Http::withHeaders(['access_token' => $apiKey])
                ->get($baseUrl . "/v3/payments/{$payId}/pixQrCode");

            if ($pixResponse->successful()) {
                $pix    = $pixResponse->json();
                $public = [
                    'type'           => 'pix',
                    'qr_code'        => data_get($pix, 'payload'),
                    'qr_code_base64' => data_get($pix, 'encodedImage'),
                    'ticket_url'     => data_get($payload, 'invoiceUrl'),
                ];
            }
        }

        if ($billingType === 'CREDIT_CARD') {
            $public = [
                'type'       => 'asaas_credit_card',
                'invoice_url'=> data_get($payload, 'invoiceUrl'),
                'message'    => 'Acesse o link para concluir o pagamento com cartao de credito.',
            ];
        }

        return $this->persist($support, [
            'gateway'     => 'asaas',
            'method'      => $method,
            'status'      => $this->normalizeStatus((string) data_get($payload, 'status')) ?: 'pending',
            'external_id' => $payId ? (string) $payId : null,
            'reference'   => $reference,
            'payment_url' => data_get($payload, 'invoiceUrl'),
            'payload'     => $payload,
            'public'      => $public,
        ]);
    }

    public function handleWebhook(Request $request): array
    {
        $event   = $request->input('event', '');
        $payment = $request->input('payment', []);

        $statusMap = [
            'PAYMENT_RECEIVED'            => 'paid',
            'PAYMENT_CONFIRMED'           => 'paid',
            'PAYMENT_OVERDUE'             => 'failed',
            'PAYMENT_DELETED'             => 'failed',
            'PAYMENT_REFUNDED'            => 'refunded',
            'PAYMENT_CHARGEBACK_DISPUTE'  => 'refunded',
        ];

        return [
            'external_id' => data_get($payment, 'id') ? (string) data_get($payment, 'id') : null,
            'reference'   => data_get($payment, 'externalReference'),
            'status'      => $statusMap[$event] ?? $this->normalizeStatus($event),
            'payload'     => $request->all(),
        ];
    }

    private function ensureCustomer(string $baseUrl, string $apiKey, ProjectSupportRequest $support): string
    {
        // Buscar cliente existente por e-mail
        if ($support->email) {
            $existing = Http::withHeaders(['access_token' => $apiKey])
                ->get($baseUrl . '/v3/customers', ['email' => $support->email]);

            if ($existing->successful() && count($existing->json('data', [])) > 0) {
                return (string) data_get($existing->json('data.0'), 'id');
            }
        }

        // Criar novo cliente
        $created = Http::withHeaders(['access_token' => $apiKey])
            ->post($baseUrl . '/v3/customers', [
                'name'              => $support->name,
                'email'             => $support->email ?: null,
                'mobilePhone'       => preg_replace('/\D/', '', (string) $support->phone) ?: null,
                'cpfCnpj'           => $support->document ? preg_replace('/\D/', '', $support->document) : null,
                'notificationDisabled' => true,
            ]);

        if (! $created->successful()) {
            throw new RuntimeException('Asaas: nao foi possivel criar o cliente: ' . $created->body());
        }

        return (string) $created->json('id');
    }

    private function credentials(): array
    {
        $mode    = Setting::get('donation_asaas_mode', 'production');
        $baseUrl = $mode === 'sandbox'
            ? 'https://homologacao.asaas.com.br/api'
            : 'https://api.asaas.com/api';
        $apiKey  = trim((string) Setting::get(
            $mode === 'sandbox' ? 'donation_asaas_api_key_sandbox' : 'donation_asaas_api_key', ''
        ));

        if (! $apiKey) {
            throw new RuntimeException('Configure a API Key do Asaas no painel administrativo.');
        }

        return [$baseUrl, $apiKey];
    }
}
