<?php

namespace App\Services\Payments;

use App\Models\ProjectSupportRequest;
use App\Models\Setting;
use App\Services\Admin\AdminNotificationMailer;
use App\Services\Payments\Drivers\AbstractGatewayDriver;
use App\Services\Payments\Drivers\AsaasDriver;
use App\Services\Payments\Drivers\CoraDriver;
use App\Services\Payments\Drivers\EfiDriver;
use App\Services\Payments\Drivers\MercadoPagoDriver;
use App\Services\Payments\Drivers\PagBankDriver;
use App\Services\Payments\Drivers\PayPalDriver;
use App\Services\Payments\Drivers\StripeDriver;
use Illuminate\Http\Request;
use RuntimeException;

class ProjectDonationGateway
{
    public const GATEWAYS = ['mercadopago', 'cora', 'pagbank', 'asaas', 'efi', 'stripe', 'paypal'];

    public const METHOD_LABELS = [
        'pix'         => 'PIX',
        'credit_card' => 'Cartao de credito',
        'debit_card'  => 'Cartao de debito',
        'paypal'      => 'PayPal',
    ];

    /** @var AbstractGatewayDriver[] */
    private array $drivers;

    public function __construct()
    {
        $this->drivers = [
            'mercadopago' => new MercadoPagoDriver(),
            'stripe'      => new StripeDriver(),
            'paypal'      => new PayPalDriver(),
            'cora'        => new CoraDriver(),
            'pagbank'     => new PagBankDriver(),
            'asaas'       => new AsaasDriver(),
            'efi'         => new EfiDriver(),
        ];
    }

    public function activeGateway(): ?string
    {
        $gateway = (string) Setting::get('donation_gateway_active', '');

        return in_array($gateway, self::GATEWAYS, true) ? $gateway : null;
    }

    public function supportedMethods(?string $gateway = null): array
    {
        $gateway = $gateway ?: $this->activeGateway();
        $driver  = $gateway ? ($this->drivers[$gateway] ?? null) : null;

        return $driver ? $driver->supportedMethods() : [];
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

        $driver = $this->drivers[$gateway] ?? null;

        if (! $driver) {
            throw new RuntimeException("Gateway '{$gateway}' nao possui driver configurado.");
        }

        $supportedMethods = $driver->supportedMethods();
        $method = in_array($method, $supportedMethods, true) ? $method : ($supportedMethods[0] ?? 'pix');

        return $driver->createPayment($support, $method, $request);
    }

    public function handleWebhook(string $gateway, Request $request): void
    {
        $driver = $this->drivers[$gateway] ?? null;

        if (! $driver) {
            return;
        }

        $result = $driver->handleWebhook($request);

        if (empty($result) || ($result['ignored'] ?? false)) {
            return;
        }

        $externalId = $result['external_id'] ?? null;
        $reference  = $result['reference']   ?? null;
        $status     = $result['status']       ?? null;
        $payload    = $result['payload']      ?? $request->all();

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

        $wasPaid    = $support->payment_status === 'paid';
        $storedPayload = $support->payment_payload ?? [];
        $storedPayload['webhooks'][] = [
            'gateway'  => $gateway,
            'at'       => now()->toDateTimeString(),
            'status'   => $status,
            'payload'  => $payload,
        ];

        $support->update([
            'payment_status'     => $status ?: $support->payment_status,
            'payment_external_id'=> $externalId ?: $support->payment_external_id,
            'status'             => $status === 'paid' ? 'completed' : $support->status,
            'paid_at'            => $status === 'paid' && ! $support->paid_at ? now() : $support->paid_at,
            'payment_payload'    => $storedPayload,
        ]);

        if ($status === 'paid' && ! $wasPaid) {
            $support->refresh()->loadMissing('project');

            app(AdminNotificationMailer::class)->send(
                'Pagamento',
                'Doacao paga confirmada',
                'O gateway confirmou automaticamente o pagamento de uma doacao.',
                route('admin.project-supports.index', ['project' => $support->project_id]) . '#apoio-' . $support->id,
                [
                    'Projeto'   => optional($support->project)->title,
                    'Nome'      => $support->name,
                    'Valor'     => 'R$ ' . number_format((float) $support->amount, 2, ',', '.'),
                    'Gateway'   => strtoupper((string) $support->payment_gateway),
                    'Referencia'=> $support->payment_reference,
                    'Data'      => optional($support->paid_at)->format('d/m/Y H:i'),
                ]
            );
        }
    }

    /** Normaliza status via driver ativo ou fallback */
    public function normalizeStatus(string $status): ?string
    {
        $driver = $this->drivers[$this->activeGateway() ?? 'mercadopago'] ?? new MercadoPagoDriver();

        return $driver->normalizeStatus($status);
    }
}
