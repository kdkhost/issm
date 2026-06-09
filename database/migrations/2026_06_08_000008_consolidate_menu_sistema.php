<?php

use App\Models\AdminMenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Atualizar o dropdown "Sistema" para incluir todos os itens
        $sistema = AdminMenuItem::where('label', 'Sistema')->where('is_dropdown', true)->first();
        if ($sistema) {
            $sistema->update([
                'children' => [
                    ['label' => 'Configurações', 'route_name' => 'admin.settings.index'],
                    ['label' => 'FAQ', 'route_name' => 'admin.faq.index'],
                    ['label' => 'Analytics', 'route_name' => 'admin.analytics.index'],
                    ['label' => 'Mensagens', 'route_name' => 'admin.contatos.index'],
                    ['label' => 'IPs Manutencao', 'route_name' => 'admin.ips-manutencao.index'],
                    ['label' => 'Central de Cron', 'route_name' => 'admin.cron.index'],
                    ['label' => 'Pastas do Drive', 'route_name' => 'admin.drive-folders.index'],
                    ['label' => 'Categorias de Transparencia', 'route_name' => 'admin.transparency-categories.index'],
                ],
            ]);
        }

        // Remover item standalone "Configurações" (duplicidade)
        AdminMenuItem::where('label', 'Configurações')->where('is_dropdown', false)->delete();
    }

    public function down(): void
    {
        // Irreversivel
    }
};
