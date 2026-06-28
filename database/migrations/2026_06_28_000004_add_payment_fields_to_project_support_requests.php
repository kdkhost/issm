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
            ['donation_mercadopago_mode', 'production', 'select', 'donations', 'Mercado Pago - Modo'],
            ['donation_mercadopago_access_token', '', 'password', 'donations', 'Mercado Pago - Access Token (producao)'],
            ['donation_mercadopago_access_token_sandbox', '', 'password', 'donations', 'Mercado Pago - Access Token (sandbox)'],
            ['donation_stripe_mode', 'production', 'select', 'donations', 'Stripe - Modo'],
            ['donation_stripe_publishable_key', '', 'text', 'donations', 'Stripe - Publishable Key (producao)'],
            ['donation_stripe_secret_key', '', 'password', 'donations', 'Stripe - Secret Key (producao)'],
            ['donation_stripe_publishable_key_sandbox', '', 'text', 'donations', 'Stripe - Publishable Key (sandbox)'],
            ['donation_stripe_secret_key_sandbox', '', 'password', 'donations', 'Stripe - Secret Key (sandbox)'],
            ['donation_paypal_mode', 'production', 'select', 'donations', 'PayPal - Modo'],
            ['donation_paypal_client_id', '', 'text', 'donations', 'PayPal - Client ID (producao)'],
            ['donation_paypal_secret', '', 'password', 'donations', 'PayPal - Secret (producao)'],
            ['donation_paypal_client_id_sandbox', '', 'text', 'donations', 'PayPal - Client ID (sandbox)'],
            ['donation_paypal_secret_sandbox', '', 'password', 'donations', 'PayPal - Secret (sandbox)'],
            ['donation_cora_mode', 'production', 'select', 'donations', 'Cora - Modo'],
            ['donation_cora_base_url', 'https://api.cora.com.br', 'text', 'donations', 'Cora - URL base da API (producao)'],
            ['donation_cora_api_key', '', 'password', 'donations', 'Cora - API Key/Token (producao)'],
            ['donation_cora_base_url_sandbox', 'https://api.sandbox.cora.com.br', 'text', 'donations', 'Cora - URL base da API (sandbox)'],
            ['donation_cora_api_key_sandbox', '', 'password', 'donations', 'Cora - API Key/Token (sandbox)'],
            ['donation_pagbank_mode', 'production', 'select', 'donations', 'PagBank - Modo'],
            ['donation_pagbank_base_url', 'https://api.pagbank.com.br', 'text', 'donations', 'PagBank - URL base da API (producao)'],
            ['donation_pagbank_api_key', '', 'password', 'donations', 'PagBank - API Key/Token (producao)'],
            ['donation_pagbank_base_url_sandbox', 'https://api.sandbox.pagbank.com.br', 'text', 'donations', 'PagBank - URL base da API (sandbox)'],
            ['donation_pagbank_api_key_sandbox', '', 'password', 'donations', 'PagBank - API Key/Token (sandbox)'],
            ['donation_asaas_mode', 'production', 'select', 'donations', 'Asaas - Modo'],
            ['donation_asaas_base_url', 'https://api.asaas.com', 'text', 'donations', 'Asaas - URL base da API (producao)'],
            ['donation_asaas_api_key', '', 'password', 'donations', 'Asaas - API Key/Token (producao)'],
            ['donation_asaas_base_url_sandbox', 'https://homologacao.asaas.com.br', 'text', 'donations', 'Asaas - URL base da API (sandbox)'],
            ['donation_asaas_api_key_sandbox', '', 'password', 'donations', 'Asaas - API Key/Token (sandbox)'],
            ['donation_efi_mode', 'production', 'select', 'donations', 'Efi Pro - Modo'],
            ['donation_efi_base_url', 'https://api.efi.com.br', 'text', 'donations', 'Efi Pro - URL base da API (producao)'],
            ['donation_efi_api_key', '', 'password', 'donations', 'Efi Pro - API Key/Token (producao)'],
            ['donation_efi_base_url_sandbox', 'https://api-hom.efi.com.br', 'text', 'donations', 'Efi Pro - URL base da API (sandbox)'],
            ['donation_efi_api_key_sandbox', '', 'password', 'donations', 'Efi Pro - API Key/Token (sandbox)'],
        ];

        foreach ($settings as [$key, $value, $type, $group, $label]) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group, 'label' => $label, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
};
