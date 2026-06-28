<?php

namespace App\Services\Payments\Drivers;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MercadoPagoDriver extends AbstractGatewayDriver
{
    public function name(): string { return 'mercadopago'; }

    public function supportedMethods(): array { return ['pix', 'credit_card']; }

    public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array
    {
        $mode  = Setting::get('donation_mercadopago_mode', 'production');
        $token = trim((string) Setting::get(
            $mode === 'sandbox' ? 'donation_mercadopago_access_token_sandbox' : 'donation_mercadopago_access_token', ''
        ));

        if (! $token) {
            throw new RuntimeException('Configure o Access Token do Mercado Pago no painel administrativo.');
        }

        if ($method === 'credit_card') {
            return $this->creditCard($support, $token, $request);
        }

        return $this->pix($support, $token);
    }

    private function pix(ProjectSupportRequest $support, string $token): array
    {
        $reference = $this->reference($support);

        $response = Http::withToken($token)
            ->withHeaders(['X-Idempotency-Key' => $reference])
            ->post('https://api.mercadopago.com/v1/payments', [
                'transaction_amount' => (float) $support->amount,
                'description'        => 'Doacao para ' . optional($support->project)->title,
                'payment_method_id'  => 'pix',
                'external_reference' => $reference,
                'notification_url'   => route('payments.webhook', ['gateway' => 'mercadopago']),
                'payer'              => [
                    'email'      => $support->email ?: Setting::get('donation_default_payer_email', 'doacao@issm.org.br'),
                    'first_name' => $support->name,
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Mercado Pago recusou a cobranca PIX: ' . $response->body());
        }

        $payload   = $response->json();
        $qr        = data_get($payload, 'point_of_interaction.transaction_data.qr_code');
        $qrBase64  = data_get($payload, 'point_of_interaction.transaction_data.qr_code_base64');
        $ticketUrl = data_get($payload, 'point_of_interaction.transaction_data.ticket_url');

        return $this->persist($support, [
            'gateway'     => 'mercadopago',
            'method'      => 'pix',
            'status'      => $this->normalizeStatus((string) data_get($payload, 'status')) ?: 'pending',
            'external_id' => (string) data_get($payload, 'id'),
            'reference'   => $reference,
            'payment_url' => $ticketUrl,
            'payload'     => $payload,
            'public'      => [
                'type'           => 'pix',
                'qr_code'        => $qr,
                'qr_code_base64' => $qrBase64,
                'ticket_url'     => $ticketUrl,
            ],
        ]);
    }

    private function creditCard(ProjectSupportRequest $support, string $token, Request $request): array
    {
        $cardToken = $request->input('card_token');

        if (! $cardToken) {
            // Retorna instruções para o frontend obter o card token via SDK JS
            return $this->persist($support, [
                'gateway'   => 'mercadopago',
                'method'    => 'credit_card',
                'status'    => 'requires_token',
                'reference' => $this->reference($support),
                'payload'   => ['message' => 'Aguardando tokenizacao do cartao via SDK JS.'],
                'public'    => [
                    'type'       => 'card_token_required',
                    'gateway'    => 'mercadopago',
                    'public_key' => Setting::get(
                        Setting::get('donation_mercadopago_mode', 'production') === 'sandbox'
                            ? 'donation_mercadopago_public_key_sandbox'
                            : 'donation_mercadopago_public_key',
                        ''
                    ),
                    'amount'     => (float) $support->amount,
                    'support_id' => $support->id,
                    'message'    => 'Informe os dados do cartao para concluir a doacao.',
                ],
            ]);
        }

        $reference = $this->reference($support);

        $response = Http::withToken($token)
            ->withHeaders(['X-Idempotency-Key' => $reference . '-cc'])
            ->post('https://api.mercadopago.com/v1/payments', [
                'transaction_amount'  => (float) $support->amount,
                'description'         => 'Doacao para ' . optional($support->project)->title,
                'payment_method_id'   => $request->input('payment_method_id', 'visa'),
                'token'               => $cardToken,
                'installments'        => max(1, (int) $request->input('installments', 1)),
                'external_reference'  => $reference,
                'notification_url'    => route('payments.webhook', ['gateway' => 'mercadopago']),
                'payer'               => [
                    'email'      => $support->email ?: Setting::get('donation_default_payer_email', 'doacao@issm.org.br'),
                    'first_name' => $support->name,
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Mercado Pago recusou o pagamento com cartao: ' . $response->body());
        }

        $payload = $response->json();
        $status  = $this->normalizeStatus((string) data_get($payload, 'status')) ?: 'pending';

        return $this->persist($support, [
            'gateway'     => 'mercadopago',
            'method'      => 'credit_card',
            'status'      => $status,
            'external_id' => (string) data_get($payload, 'id'),
            'reference'   => $reference,
            'payload'     => $payload,
            'public'      => [
                'type'    => $status === 'paid' ? 'paid' : 'pending',
                'message' => $status === 'paid'
                    ? 'Pagamento aprovado com sucesso.'
                    : 'Pagamento em processamento. Voce sera notificado por e-mail.',
            ],
        ]);
    }

    public function handleWebhook(Request $request): array
    {
        $externalId = $request->input('data.id') ?: $request->input('id');
        $reference  = $request->input('external_reference') ?: $request->input('reference');

        if (! $externalId && ! $reference) {
            return ['ignored' => true];
        }

        // Buscar detalhes do pagamento na API para obter o status real
        if ($externalId) {
            $mode  = Setting::get('donation_mercadopago_mode', 'production');
            $token = Setting::get(
                $mode === 'sandbox' ? 'donation_mercadopago_access_token_sandbox' : 'donation_mercadopago_access_token', ''
            );

            if ($token) {
                $detail = Http::withToken($token)->get("https://api.mercadopago.com/v1/payments/{$externalId}");
                if ($detail->successful()) {
                    $data      = $detail->json();
                    $status    = $this->normalizeStatus((string) data_get($data, 'status'));
                    $reference = $reference ?: data_get($data, 'external_reference');

                    return [
                        'external_id' => (string) $externalId,
                        'reference'   => $reference,
                        'status'      => $status,
                        'payload'     => $data,
                    ];
                }
            }
        }

        return [
            'external_id' => $externalId ? (string) $externalId : null,
            'reference'   => $reference,
            'status'      => $this->normalizeStatus((string) $request->input('status')),
            'payload'     => $request->all(),
        ];
    }
}
