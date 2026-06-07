<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Atualiza os labels de reCAPTCHA de v2 para v3
        DB::table('settings')->where('key', 'recaptcha_site_key')->update([
            'label' => 'reCAPTCHA Site Key (v3)'
        ]);

        DB::table('settings')->where('key', 'recaptcha_secret_key')->update([
            'label' => 'reCAPTCHA Secret Key (v3)'
        ]);

        // Se as chaves não existirem, cria-as com os labels v3
        DB::table('settings')->updateOrInsert(
            ['key' => 'recaptcha_site_key'],
            [
                'value' => '',
                'type' => 'text',
                'group' => 'security',
                'label' => 'reCAPTCHA Site Key (v3)',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'recaptcha_secret_key'],
            [
                'value' => '',
                'type' => 'password',
                'group' => 'security',
                'label' => 'reCAPTCHA Secret Key (v3)',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        // Reverte para v2 se necessário
        DB::table('settings')->where('key', 'recaptcha_site_key')->update([
            'label' => 'reCAPTCHA Site Key (v2)'
        ]);

        DB::table('settings')->where('key', 'recaptcha_secret_key')->update([
            'label' => 'reCAPTCHA Secret Key (v2)'
        ]);
    }
};
