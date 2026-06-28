<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_support_requests', function (Blueprint $table) {
            $table->string('payment_gateway', 40)->nullable()->after('currency')->index();
            $table->string('payment_method', 40)->nullable()->after('payment_gateway')->index();
            $table->string('payment_status', 40)->nullable()->after('payment_method')->index();
            $table->string('payment_external_id')->nullable()->after('payment_status')->index();
            $table->string('payment_reference')->nullable()->after('payment_external_id')->index();
            $table->text('payment_url')->nullable()->after('payment_reference');
            $table->longText('payment_payload')->nullable()->after('payment_url');
            $table->timestamp('paid_at')->nullable()->after('contacted_at');
        });

        $this->seedGatewaySettings();
    }

    public function down(): void
    {
        Schema::table('project_support_requests', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'payment_method',
                'payment_status',
                'payment_external_id',
                'payment_reference',
                'payment_url',
                'payment_payload',
                'paid_at',
            ]);
        });
    }

    private function seedGatewaySettings(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $settings = [
            ['donation_gateway_active', '', 'select', 'donations', 'Gateway ativo para doacoes'],
            ['donation_default_payer_email', 'doacao@issm.org.br', 'text', 'donations', 'E-mail padrao para PIX sem e-mail do doador'],
            ['donation_mercadopago_access_token', '', 'password', 'donations', 'Mercado Pago - Access Token'],
            ['donation_stripe_publishable_key', '', 'text', 'donations', 'Stripe - Publishable Key'],
            ['donation_stripe_secret_key', '', 'password', 'donations', 'Stripe - Secret Key'],
            ['donation_paypal_client_id', '', 'text', 'donations', 'PayPal - Client ID'],
            ['donation_paypal_secret', '', 'password', 'donations', 'PayPal - Secret'],
            ['donation_paypal_sandbox', '1', 'boolean', 'donations', 'PayPal - Usar sandbox'],
            ['donation_cora_base_url', '', 'text', 'donations', 'Cora - URL base da API'],
            ['donation_cora_api_key', '', 'password', 'donations', 'Cora - API Key/Token'],
            ['donation_pagbank_base_url', '', 'text', 'donations', 'PagBank - URL base da API'],
            ['donation_pagbank_api_key', '', 'password', 'donations', 'PagBank - API Key/Token'],
            ['donation_asaas_base_url', '', 'text', 'donations', 'Asaas - URL base da API'],
            ['donation_asaas_api_key', '', 'password', 'donations', 'Asaas - API Key/Token'],
            ['donation_efi_base_url', '', 'text', 'donations', 'Efí Pro - URL base da API'],
            ['donation_efi_api_key', '', 'password', 'donations', 'Efí Pro - API Key/Token'],
        ];

        foreach ($settings as [$key, $value, $type, $group, $label]) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group, 'label' => $label, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
};
