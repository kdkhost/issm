<?php

namespace App\Services\Payments\Drivers;

use App\Models\ProjectSupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class AbstractGatewayDriver
{
    /** Identificador do gateway */
    abstract public function name(): string;

    /** Métodos de pagamento suportados */
    abstract public function supportedMethods(): array;

    /** Criar cobrança e retornar dados públicos para o frontend */
    abstract public function createPayment(ProjectSupportRequest $support, string $method, Request $request): array;

    /** Processar webhook e retornar array normalizado com external_id, reference, status, payload */
    abstract public function handleWebhook(Request $request): array;

    // ─── Helpers compartilhados ───────────────────────────────────────────────

    protected function reference(ProjectSupportRequest $support): string
    {
        return $support->payment_reference
            ?: 'ISSM-APOIO-' . $support->id . '-' . Str::upper(Str::random(8));
    }

    public function normalizeStatus(string $status): ?string
    {
        $s = Str::lower(trim($status));

        return match (true) {
            in_array($s, [
                'approved', 'paid', 'payment.received', 'checkout.session.completed',
                'payment_intent.succeeded', 'charge.succeeded', 'payment_confirmed',
                'captured', 'settled', 'complete', 'completed',
            ], true) => 'paid',

            in_array($s, [
                'rejected', 'cancelled', 'canceled', 'failed', 'payment.failed',
                'charge.failed', 'declined', 'error',
            ], true) => 'failed',

            in_array($s, [
                'refunded', 'charged_back', 'reversed', 'chargeback',
            ], true) => 'refunded',

            in_array($s, [
                'pending', 'in_process', 'created', 'requires_payment_method',
                'requires_confirmation', 'requires_action', 'waiting', 'processing',
                'in_analysis', 'authorized', 'generated',
            ], true) => 'pending',

            default => null,
        };
    }

    protected function persist(ProjectSupportRequest $support, array $data): array
    {
        $support->update([
            'payment_gateway'    => $data['gateway'],
            'payment_method'     => $data['method'],
            'payment_status'     => $data['status'],
            'payment_external_id'=> $data['external_id'] ?? null,
            'payment_reference'  => $data['reference'],
            'payment_url'        => $data['payment_url'] ?? null,
            'payment_payload'    => $data['payload'] ?? null,
        ]);

        return [
            'support_id'  => $support->id,
            'gateway'     => $data['gateway'],
            'method'      => $data['method'],
            'status'      => $data['status'],
            'reference'   => $data['reference'],
            'payment_url' => $data['payment_url'] ?? null,
            'public'      => $data['public'] ?? [],
        ];
    }
}
