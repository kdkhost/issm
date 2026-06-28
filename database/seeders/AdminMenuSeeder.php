<?php

namespace Database\Seeders;

use App\Models\AdminMenuItem;
use Illuminate\Database\Seeder;

class AdminMenuSeeder extends Seeder
{
    public function run(): void
    {
        AdminMenuItem::truncate();

        $items = [
            [
                'label' => 'Dashboard',
                'route_name' => 'admin.dashboard',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                'group_label' => null,
                'sort_order' => 1,
                'is_active' => true,
                'is_dropdown' => false,
            ],
            [
                'label' => 'Conteúdo',
                'route_name' => '#',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
                'group_label' => null,
                'sort_order' => 2,
                'is_active' => true,
                'is_dropdown' => true,
                'children' => [
                    ['label' => 'Banners', 'route_name' => 'admin.banners.index'],
                    ['label' => 'Notícias', 'route_name' => 'admin.noticias.index'],
                    ['label' => 'Projetos', 'route_name' => 'admin.projetos.index'],
                    ['label' => 'Apoios aos Projetos', 'route_name' => 'admin.project-supports.index'],
                    ['label' => 'Galeria', 'route_name' => 'admin.galeria.index'],
                    ['label' => 'CMS Páginas Públicas', 'route_name' => 'admin.cms-public-pages.index'],
                    ['label' => 'Páginas Dinâmicas', 'route_name' => 'admin.paginas.index'],
                    ['label' => 'Transparência', 'route_name' => 'admin.transparencia.index'],
                ],
            ],
            [
                'label' => 'Institucional',
                'route_name' => '#',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                'group_label' => null,
                'sort_order' => 3,
                'is_active' => true,
                'is_dropdown' => true,
                'children' => [
                    ['label' => 'Equipe', 'route_name' => 'admin.equipe.index'],
                    ['label' => 'Depoimentos', 'route_name' => 'admin.depoimentos.index'],
                    ['label' => 'Parceiros', 'route_name' => 'admin.parceiros.index'],
                    ['label' => 'ODS 2030', 'route_name' => 'admin.ods.index'],
                ],
            ],
            [
                'label' => 'Sistema',
                'route_name' => '#',
                'icon_svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>',
                'group_label' => null,
                'sort_order' => 5,
                'is_active' => true,
                'is_dropdown' => true,
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
            ],
        ];

        foreach ($items as $item) {
            AdminMenuItem::create($item);
        }
    }
}
