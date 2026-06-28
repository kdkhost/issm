<?php

namespace App\Services\Payments\Drivers;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class StripeDriver extends AbstractGatewayDriver
{
    public function name(): string { return 'stripe'; }

    public function supportedMethods(): array { return ['credit_card', 'debit_card']; }

    public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array
    {
        $mode        = Setting::get('donation_stripe_mode', 'production');
        $secret      = trim((string) Setting::get($mode === 'sandbox' ? 'donation_stripe_secret_key_sandbox' : 'donation_stripe_secret_key', ''));
        $publishable = trim((string) Setting::get($mode === 'sandbox' ? 'donation_stripe_publishable_key_sandbox' : 'donation_stripe_publishable_key', ''));

        if (! $secret) {
            throw new RuntimeException('Configure a Secret Key da Stripe no painel administrativo.');
        }

        $reference = $this->reference($support);

        // Criar Checkout Session (redirect) — mais simples e não exige SDK JS no frontend
        $response = Http::asForm()
            ->withToken($secret)
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'mode'                              => 'payment',
                'currency'                          => 'brl',
                'success_url'                       => route('payments.return', ['gateway' => 'stripe', 'reference' => $reference]),
                'cancel_url'                        => route('payments.return', ['gateway' => 'stripe', 'reference' => $reference, 'cancelled' => 1]),
                'line_items[0][quantity]'           => 1,
                'line_items[0][price_data][currency]'           => 'brl',
                'line_items[0][price_data][unit_amount]'        => (int) round((float) $support->amount * 100),
                'line_items[0][price_data][product_data][name]' => 'Doacao para ' . optional($support->project)->title,
                'metadata[support_reference]'       => $reference,
                'metadata[support_id]'              => $support->id,
                'metadata[project_id]'              => $support->project_id,
                'customer_email'                    => $support->email ?: null,
                'payment_intent_data[metadata][support_reference]' => $reference,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Stripe recusou a criacao da sessao de checkout: ' . $response->body());
        }

        $payload    = $response->json();
        $sessionUrl = data_get($payload, 'url');

        return $this->persist($support, [
            'gateway'     => 'stripe',
            'method'      => $method,
            'status'      => 'pending',
            'external_id' => (string) data_get($payload, 'id'),
            'reference'   => $reference,
            'payment_url' => $sessionUrl,
            'payload'     => $payload,
            'public'      => [
                'type'    => 'redirect',
                'url'     => $sessionUrl,
                'message' => 'Voce sera redirecionado para concluir o pagamento com seguranca na Stripe.',
            ],
        ]);
    }

    public function handleWebhook(Request $request): array
    {
        $event  = $request->input('type', '');
        $object = $request->input('data.object', []);

        $externalId = data_get($object, 'payment_intent')
            ?: data_get($object, 'id');

        $reference = data_get($object, 'metadata.support_reference')
            ?: data_get($object, 'payment_intent_data.metadata.support_reference');

        return [
            'external_id' => $externalId ? (string) $externalId : null,
            'reference'   => $reference,
            'status'      => $this->normalizeStatus($event),
            'payload'     => $request->all(),
        ];
    }
}
