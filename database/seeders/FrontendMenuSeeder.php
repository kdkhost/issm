<?php

namespace Database\Seeders;

use App\Models\FrontendMenuItem;
use Illuminate\Database\Seeder;

class FrontendMenuSeeder extends Seeder
{
    public function run(): void
    {
        FrontendMenuItem::truncate();

        $items = [
            [
                'label' => 'Início',
                'route_name' => 'home',
                'icon_svg' => '<svg style="width:20px;height:20px;color:#15803d;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                'icon_bg_color' => '#dcfce7',
                'icon_color' => '#15803d',
                'is_button' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'label' => 'Sobre',
                'route_name' => 'about.index',
                'icon_svg' => '<svg style="width:20px;height:20px;color:#1d4ed8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'icon_bg_color' => '#dbeafe',
                'icon_color' => '#1d4ed8',
                'is_button' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'label' => 'Projetos',
                'route_name' => 'projects.index',
                'icon_svg' => '<svg style="width:20px;height:20px;color:#c2410c;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                'icon_bg_color' => '#ffedd5',
                'icon_color' => '#c2410c',
                'is_button' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'label' => 'ODS 2030',
                'route_name' => 'ods.index',
                'icon_svg' => '<svg style="width:20px;height:20px;color:#065f46;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'icon_bg_color' => '#d1fae5',
                'icon_color' => '#065f46',
                'is_button' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'label' => 'Notícias',
                'route_name' => 'news.index',
                'icon_svg' => '<svg style="width:20px;height:20px;color:#6d28d9;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>',
                'icon_bg_color' => '#ede9fe',
                'icon_color' => '#6d28d9',
                'is_button' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'label' => 'Galeria',
                'route_name' => 'gallery.index',
                'icon_svg' => '<svg style="width:20px;height:20px;color:#be185d;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                'icon_bg_color' => '#fce7f3',
                'icon_color' => '#be185d',
                'is_button' => false,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'label' => 'Transparência',
                'route_name' => 'transparency.index',
                'icon_svg' => '<svg style="width:20px;height:20px;color:#15803d;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                'icon_bg_color' => '#f0fdf4',
                'icon_color' => '#15803d',
                'is_button' => false,
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'label' => 'Contato',
                'route_name' => 'contact.index',
                'icon_svg' => '<svg style="width:20px;height:20px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                'icon_bg_color' => '#15803d',
                'icon_color' => '#fff',
                'is_button' => true,
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            FrontendMenuItem::create($item);
        }
    }
}
