<?php

namespace App\Services\Payments;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use App\Services\Admin\AdminNotificationMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class ProjectDonationGateway
{
    public const GATEWAYS = ['mercadopago', 'cora', 'pagbank', 'asaas', 'efi', 'stripe', 'paypal'];

    public const METHOD_LABELS = [
        'pix' => 'PIX',
        'credit_card' => 'Cartao de credito',
        'debit_card' => 'Cartao de debito',
        'paypal' => 'PayPal',
    ];

    public function activeGateway(): ?string
    {
        $gateway = (string) Setting::get('donation_gateway_active', '');

        return in_array($gateway, self::GATEWAYS, true) ? $gateway : null;
    }

    public function supportedMethods(?string $gateway = null): array
    {
        $gateway = $gateway ?: $this->activeGateway();

        return match ($gateway) {
            'mercadopago' => ['pix'],
            'stripe' => ['credit_card', 'debit_card'],
            'paypal' => ['paypal'],
            'cora', 'asaas', 'efi' => ['pix'],
            'pagbank' => ['pix', 'credit_card', 'debit_card'],
            default => [],
        };
    }

    public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array
    {
        $gateway = $this->activeGateway();

        if (! $gateway) {
            throw new RuntimeException('Nenhum gateway de doacao esta ativo no painel administrativo.');
        }

        if ((float) $support->amount <= 0) {
            throw new RuntimeException('Informe um valor valido para a doacao monetaria.');
        }

        $supportedMethods = $this->supportedMethods($gateway);
        $method = in_array($method, $supportedMethods, true) ? $method : ($supportedMethods[0] ?? 'pix');

        if (! in_array($method, $supportedMethods, true)) {
            throw new RuntimeException('O gateway ativo nao possui forma de pagamento habilitada.');
        }

        return match ($gateway) {
            'mercadopago' => $this->mercadoPago($support, $method, $request),
            'stripe' => $this->stripe($support, $method),
            'paypal' => $this->paypal($support),
            default => $this->genericConfiguredGateway($support, $method, $gateway),
        };
    }

    public function handleWebhook(string $gateway, Request $request): void
    {
        $externalId = $request->input('data.id')
            ?: $request->input('id')
            ?: $request->input('payment.id')
            ?: $request->input('resource.id')
            ?: $request->input('external_reference')
            ?: $request->input('reference');

        $reference = $request->input('external_reference')
            ?: $request->input('reference')
            ?: $request->input('metadata.reference')
            ?: $request->input('metadata.support_reference');

        if (! $externalId && ! $reference) {
            return;
        }

        $support = ProjectSupportRequest::query()
            ->where(function ($query) use ($externalId, $reference) {
                if ($externalId) {
                    $query->orWhere('payment_external_id', (string) $externalId);
                }

                if ($reference) {
                    $query->orWhere('payment_reference', (string) $reference);
                }
            })
            ->latest()
            ->first();

        if (! $support) {
            return;
        }

        $status = $this->normalizeStatus((string) (
            $request->input('status')
            ?: $request->input('payment.status')
            ?: $request->input('event')
            ?: $request->input('type')
        ));

        $payload = $support->payment_payload ?? [];
        $payload['last_webhook'] = $request->all();
        $payload['last_webhook_at'] = now()->toDateTimeString();

        $wasPaid = $support->payment_status === 'paid';

        $support->update([
            'payment_status' => $status ?: $support->payment_status,
            'status' => $status === 'paid' ? 'completed' : $support->status,
            'paid_at' => $status === 'paid' && ! $support->paid_at ? now() : $support->paid_at,
            'payment_payload' => $payload,
        ]);

        if ($status === 'paid' && ! $wasPaid) {
            $support->refresh()->loadMissing('project');

            app(AdminNotificationMailer::class)->send(
                'Pagamento',
                'Doacao paga confirmada',
                'O gateway confirmou automaticamente o pagamento de uma doacao.',
                route('admin.project-supports.index', ['project' => $support->project_id]) . '#apoio-' . $support->id,
                [
                    'Projeto' => optional($support->project)->title,
                    'Nome' => $support->name,
                    'Valor' => 'R$ ' . number_format((float) $support->amount, 2, ',', '.'),
                    'Gateway' => strtoupper((string) $support->payment_gateway),
                    'Referencia' => $support->payment_reference,
                    'Data' => optional($support->paid_at)->format('d/m/Y H:i'),
                ]
            );
        }
    }

    public function normalizeStatus(string $status): ?string
    {
        $status = Str::lower($status);

        return match (true) {
            in_array($status, ['approved', 'paid', 'payment.received', 'checkout.session.completed', 'payment_intent.succeeded'], true) => 'paid',
            in_array($status, ['rejected', 'cancelled', 'canceled', 'failed', 'payment.failed'], true) => 'failed',
            in_array($status, ['refunded', 'charged_back'], true) => 'refunded',
            in_array($status, ['pending', 'in_process', 'created', 'requires_payment_method', 'requires_confirmation'], true) => 'pending',
            default => null,
        };
    }

    private function mercadoPago(ProjectSupportRequest $support, string $method, Request $request): array
    {
        $mode = Setting::get('donation_mercadopago_mode', 'production');
        $token = trim((string) Setting::get($mode === 'sandbox' ? 'donation_mercadopago_access_token_sandbox' : 'donation_mercadopago_access_token', ''));

        if (! $token) {
            throw new RuntimeException('Configure o Access Token do Mercado Pago no painel.');
        }

        if ($method !== 'pix') {
            return $this->cardTokenRequired($support, 'mercadopago', $method);
        }

        $reference = $this->reference($support);
        $response = Http::withToken($token)
            ->withHeaders(['X-Idempotency-Key' => $reference])
            ->post('https://api.mercadopago.com/v1/payments', [
                'transaction_amount' => (float) $support->amount,
                'description' => 'Doacao para ' . optional($support->project)->title,
                'payment_method_id' => 'pix',
                'external_reference' => $reference,
                'notification_url' => route('payments.webhook', ['gateway' => 'mercadopago']),
                'payer' => [
                    'email' => $support->email ?: Setting::get('donation_default_payer_email', 'doacao@issm.org.br'),
                    'first_name' => $support->name,
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Mercado Pago recusou a criacao da cobranca: ' . $response->body());
        }

        $payload = $response->json();
        $qr = data_get($payload, 'point_of_interaction.transaction_data.qr_code');
        $qrBase64 = data_get($payload, 'point_of_interaction.transaction_data.qr_code_base64');

        return $this->persistPayment($support, [
            'gateway' => 'mercadopago',
            'method' => 'pix',
            'status' => $this->normalizeStatus((string) data_get($payload, 'status')) ?: 'pending',
            'external_id' => (string) data_get($payload, 'id'),
            'reference' => $reference,
            'payment_url' => data_get($payload, 'point_of_interaction.transaction_data.ticket_url'),
            'payload' => $payload,
            'public' => [
                'type' => 'pix',
                'qr_code' => $qr,
                'qr_code_base64' => $qrBase64,
                'ticket_url' => data_get($payload, 'point_of_interaction.transaction_data.ticket_url'),
            ],
        ]);
    }

    private function stripe(ProjectSupportRequest $support, string $method): array
    {
        $mode = Setting::get('donation_stripe_mode', 'production');
        $secret = trim((string) Setting::get($mode === 'sandbox' ? 'donation_stripe_secret_key_sandbox' : 'donation_stripe_secret_key', ''));
        $publishable = Setting::get($mode === 'sandbox' ? 'donation_stripe_publishable_key_sandbox' : 'donation_stripe_publishable_key', '');

        if (! $secret) {
            throw new RuntimeException('Configure a Secret Key da Stripe no painel.');
        }

        $reference = $this->reference($support);
        $response = Http::asForm()
            ->withToken($secret)
            ->post('https://api.stripe.com/v1/payment_intents', [
                'amount' => (int) round(((float) $support->amount) * 100),
                'currency' => 'brl',
                'automatic_payment_methods[enabled]' => 'true',
                'metadata[support_reference]' => $reference,
                'metadata[project_id]' => $support->project_id,
                'description' => 'Doacao para ' . optional($support->project)->title,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Stripe recusou a criacao do pagamento: ' . $response->body());
        }

        $payload = $response->json();

        return $this->persistPayment($support, [
            'gateway' => 'stripe',
            'method' => $method,
            'status' => $this->normalizeStatus((string) data_get($payload, 'status')) ?: 'pending',
            'external_id' => (string) data_get($payload, 'id'),
            'reference' => $reference,
            'payload' => $payload,
            'public' => [
                'type' => 'stripe_payment_intent',
                'client_secret' => data_get($payload, 'client_secret'),
                'publishable_key' => $publishable,
            ],
        ]);
    }

    private function paypal(ProjectSupportRequest $support): array
    {
        $mode = Setting::get('donation_paypal_mode', 'production');
        $clientId = trim((string) Setting::get($mode === 'sandbox' ? 'donation_paypal_client_id_sandbox' : 'donation_paypal_client_id', ''));
        $secret = trim((string) Setting::get($mode === 'sandbox' ? 'donation_paypal_secret_sandbox' : 'donation_paypal_secret', ''));
        $baseUrl = $mode === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';

        if (! $clientId || ! $secret) {
            throw new RuntimeException('Configure Client ID e Secret do PayPal no painel.');
        }

        $token = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post($baseUrl . '/v1/oauth2/token', ['grant_type' => 'client_credentials']);

        if (! $token->successful()) {
            throw new RuntimeException('PayPal nao retornou token de acesso: ' . $token->body());
        }

        $reference = $this->reference($support);
        $response = Http::withToken((string) $token->json('access_token'))
            ->post($baseUrl . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $reference,
                    'description' => 'Doacao para ' . optional($support->project)->title,
                    'amount' => ['currency_code' => 'BRL', 'value' => number_format((float) $support->amount, 2, '.', '')],
                ]],
                'application_context' => [
                    'return_url' => route('payments.return', ['gateway' => 'paypal', 'reference' => $reference]),
                    'cancel_url' => route('payments.return', ['gateway' => 'paypal', 'reference' => $reference, 'cancelled' => 1]),
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('PayPal recusou a criacao da ordem: ' . $response->body());
        }

        $payload = $response->json();
        $approvalUrl = collect($payload['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;

        return $this->persistPayment($support, [
            'gateway' => 'paypal',
            'method' => 'paypal',
            'status' => 'pending',
            'external_id' => (string) data_get($payload, 'id'),
            'reference' => $reference,
            'payment_url' => $approvalUrl,
            'payload' => $payload,
            'public' => ['type' => 'redirect', 'url' => $approvalUrl, 'message' => 'Voce sera redirecionado para concluir com seguranca no PayPal.'],
        ]);
    }

    private function genericConfiguredGateway(ProjectSupportRequest $support, string $method, string $gateway): array
    {
        $defaultUrls = [
            'cora' => ['production' => 'https://api.cora.com.br', 'sandbox' => 'https://api.sandbox.cora.com.br'],
            'pagbank' => ['production' => 'https://api.pagbank.com.br', 'sandbox' => 'https://api.sandbox.pagbank.com.br'],
            'asaas' => ['production' => 'https://api.asaas.com', 'sandbox' => 'https://homologacao.asaas.com.br'],
            'efi' => ['production' => 'https://api.efi.com.br', 'sandbox' => 'https://api-hom.efi.com.br'],
        ];

        $mode = Setting::get("donation_{$gateway}_mode", 'production');
        $baseUrl = trim((string) Setting::get($mode === 'sandbox' ? "donation_{$gateway}_base_url_sandbox" : "donation_{$gateway}_base_url", ''));
        $token = trim((string) Setting::get($mode === 'sandbox' ? "donation_{$gateway}_api_key_sandbox" : "donation_{$gateway}_api_key", ''));

        $baseUrl = $baseUrl ?: ($defaultUrls[$gateway][$mode] ?? '');

        return $this->persistPayment($support, [
            'gateway' => $gateway,
            'method' => $method,
            'status' => 'pending',
            'external_id' => null,
            'reference' => $this->reference($support),
            'payload' => ['message' => 'Gateway configurado para integracao transparente via endpoint dinamico.', 'base_url' => $baseUrl, 'mode' => $mode],
            'public' => ['type' => 'pending_gateway', 'message' => 'A doacao foi registrada. Este gateway exige credenciais e homologacao do provedor para concluir a cobranca transparente.'],
        ]);
    }

    private function cardTokenRequired(ProjectSupportRequest $support, string $gateway, string $method): array
    {
        return $this->persistPayment($support, [
            'gateway' => $gateway,
            'method' => $method,
            'status' => 'requires_token',
            'reference' => $this->reference($support),
            'payload' => ['message' => 'Cartao exige tokenizacao transparente pelo SDK JS do gateway.'],
            'public' => ['type' => 'card_token_required', 'gateway' => $gateway, 'message' => 'Pagamento registrado. Finalizacao por cartao exige tokenizacao transparente do gateway ativo.'],
        ]);
    }

    private function persistPayment(ProjectSupportRequest $support, array $data): array
    {
        $support->update([
            'payment_gateway' => $data['gateway'],
            'payment_method' => $data['method'],
            'payment_status' => $data['status'],
            'payment_external_id' => $data['external_id'] ?? null,
            'payment_reference' => $data['reference'],
            'payment_url' => $data['payment_url'] ?? null,
            'payment_payload' => $data['payload'] ?? null,
        ]);

        return [
            'support_id' => $support->id,
            'gateway' => $data['gateway'],
            'method' => $data['method'],
            'status' => $data['status'],
            'reference' => $data['reference'],
            'payment_url' => $data['payment_url'] ?? null,
            'public' => $data['public'] ?? [],
        ];
    }

    private function reference(ProjectSupportRequest $support): string
    {
        return $support->payment_reference ?: 'ISSM-APOIO-' . $support->id . '-' . Str::upper(Str::random(8));
    }
}
