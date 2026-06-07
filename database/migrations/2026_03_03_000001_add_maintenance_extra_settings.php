<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $extras = [
            ['key' => 'maintenance_logo',           'value' => '',       'type' => 'image',   'group' => 'maintenance', 'label' => 'Logo da Manutenção'],
            ['key' => 'maintenance_bg_image',        'value' => '',       'type' => 'image',   'group' => 'maintenance', 'label' => 'Imagem de Fundo'],
            ['key' => 'maintenance_bg_color',        'value' => '#14532d','type' => 'text',    'group' => 'maintenance', 'label' => 'Cor de Fundo (hex)'],
            ['key' => 'maintenance_animation',       'value' => 'gear',   'type' => 'text',    'group' => 'maintenance', 'label' => 'Animação (gear/pulse/wave/none)'],
            ['key' => 'maintenance_show_countdown',  'value' => '0',      'type' => 'boolean', 'group' => 'maintenance', 'label' => 'Exibir Contagem Regressiva'],
            ['key' => 'maintenance_return_date',     'value' => '',       'type' => 'text',    'group' => 'maintenance', 'label' => 'Data/Hora de Retorno (YYYY-MM-DDTHH:MM)'],
            ['key' => 'maintenance_show_social',     'value' => '1',      'type' => 'boolean', 'group' => 'maintenance', 'label' => 'Exibir Redes Sociais'],
            ['key' => 'maintenance_progress',        'value' => '65',     'type' => 'text',    'group' => 'maintenance', 'label' => 'Progresso (0-100%)'],
        ];

        foreach ($extras as $s) {
            DB::table('settings')->updateOrInsert(['key' => $s['key']], array_merge($s, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        $keys = [
            'maintenance_logo','maintenance_bg_image','maintenance_bg_color',
            'maintenance_animation','maintenance_show_countdown','maintenance_return_date',
            'maintenance_show_social','maintenance_progress',
        ];
        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
