<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            [
                'key'   => 'turnstile_site_key',
                'value' => '',
                'type'  => 'text',
                'group' => 'security',
                'label' => 'Turnstile Site Key (Cloudflare)',
            ],
            [
                'key'   => 'turnstile_secret_key',
                'value' => '',
                'type'  => 'password',
                'group' => 'security',
                'label' => 'Turnstile Secret Key (Cloudflare)',
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'turnstile_site_key',
            'turnstile_secret_key',
        ])->delete();
    }
};
