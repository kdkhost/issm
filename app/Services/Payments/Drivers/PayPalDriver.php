<?php

namespace App\Services\Payments\Drivers;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalDriver extends AbstractGatewayDriver
{
    public function name(): string { return 'paypal'; }

    public function supportedMethods(): array { return ['paypal']; }

    public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array
    {
        $mode      = Setting::get('donation_paypal_mode', 'production');
        $clientId  = trim((string) Setting::get($mode === 'sandbox' ? 'donation_paypal_client_id_sandbox' : 'donation_paypal_client_id', ''));
        $secret    = trim((string) Setting::get($mode === 'sandbox' ? 'donation_paypal_secret_sandbox' : 'donation_paypal_secret', ''));
        $baseUrl   = $mode === 'sandbox' ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';

        if (! $clientId || ! $secret) {
            throw new RuntimeException('Configure o Client ID e Secret do PayPal no painel administrativo.');
        }

        // Obter token OAuth
        $tokenResponse = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post($baseUrl . '/v1/oauth2/token', ['grant_type' => 'client_credentials']);

        if (! $tokenResponse->successful()) {
            throw new RuntimeException('PayPal nao retornou token de acesso: ' . $tokenResponse->body());
        }

        $accessToken = (string) $tokenResponse->json('access_token');
        $reference   = $this->reference($support);

        $response = Http::withToken($accessToken)
            ->post($baseUrl . '/v2/checkout/orders', [
                'intent'         => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $reference,
                    'description'  => 'Doacao para ' . optional($support->project)->title,
                    'amount'       => [
                        'currency_code' => 'BRL',
                        'value'         => number_format((float) $support->amount, 2, '.', ''),
                    ],
                    'custom_id'    => $reference,
                ]],
                'application_context' => [
                    'brand_name'          => 'ISSM',
                    'locale'              => 'pt-BR',
                    'landing_page'        => 'BILLING',
                    'user_action'         => 'PAY_NOW',
                    'return_url'          => route('payments.return', ['gateway' => 'paypal', 'reference' => $reference]),
                    'cancel_url'          => route('payments.return', ['gateway' => 'paypal', 'reference' => $reference, 'cancelled' => 1]),
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('PayPal recusou a criacao da ordem: ' . $response->body());
        }

        $payload     = $response->json();
        $approvalUrl = collect($payload['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;

        return $this->persist($support, [
            'gateway'     => 'paypal',
            'method'      => 'paypal',
            'status'      => 'pending',
            'external_id' => (string) data_get($payload, 'id'),
            'reference'   => $reference,
            'payment_url' => $approvalUrl,
            'payload'     => $payload,
            'public'      => [
                'type'    => 'redirect',
                'url'     => $approvalUrl,
                'message' => 'Voce sera redirecionado para o PayPal para concluir a doacao com seguranca.',
            ],
        ]);
    }

    public function handleWebhook(Request $request): array
    {
        $eventType  = $request->input('event_type', '');
        $resource   = $request->input('resource', []);

        $externalId = data_get($resource, 'id');
        $reference  = data_get($resource, 'purchase_units.0.reference_id')
            ?: data_get($resource, 'purchase_units.0.custom_id')
            ?: data_get($resource, 'custom_id');

        $statusMap = [
            'PAYMENT.CAPTURE.COMPLETED'  => 'paid',
            'CHECKOUT.ORDER.APPROVED'    => 'pending',
            'PAYMENT.CAPTURE.DENIED'     => 'failed',
            'PAYMENT.CAPTURE.REVERSED'   => 'refunded',
            'PAYMENT.CAPTURE.REFUNDED'   => 'refunded',
        ];

        $status = $statusMap[$eventType] ?? $this->normalizeStatus($eventType);

        return [
            'external_id' => $externalId ? (string) $externalId : null,
            'reference'   => $reference,
            'status'      => $status,
            'payload'     => $request->all(),
        ];
    }
}
