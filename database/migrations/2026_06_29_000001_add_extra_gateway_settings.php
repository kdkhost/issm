<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $extra = [
            // MercadoPago — Public Key para SDK JS de cartão
            ['donation_mercadopago_public_key',         '', 'text',     'donations', 'Mercado Pago - Public Key (producao)'],
            ['donation_mercadopago_public_key_sandbox',  '', 'text',     'donations', 'Mercado Pago - Public Key (sandbox)'],

            // PagBank — Public Key para criptografia de cartão
            ['donation_pagbank_public_key',              '', 'text',     'donations', 'PagBank - Public Key'],

            // Efi — Client ID (separado da API Key/Secret)
            ['donation_efi_client_id',                   '', 'text',     'donations', 'Efi Pro - Client ID (producao)'],
            ['donation_efi_client_id_sandbox',           '', 'text',     'donations', 'Efi Pro - Client ID (sandbox)'],
            // Efi — Chave PIX cadastrada na conta
            ['donation_efi_pix_key',                     '', 'text',     'donations', 'Efi Pro - Chave PIX (CPF, CNPJ, email ou aleatoria)'],
        ];

        foreach ($extra as [$key, $value, $type, $group, $label]) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group, 'label' => $label, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'donation_mercadopago_public_key',
            'donation_mercadopago_public_key_sandbox',
            'donation_pagbank_public_key',
            'donation_efi_client_id',
            'donation_efi_client_id_sandbox',
            'donation_efi_pix_key',
        ])->delete();
    }
};
