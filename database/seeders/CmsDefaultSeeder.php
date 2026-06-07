<?php

namespace Database\Seeders;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsDefaultSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Create default CMS settings
            $settings = [
                'cms_version' => '1.0.0',
                'cms_cache_enabled' => '1',
                'cms_audit_enabled' => '1',
            ];

            foreach ($settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
                );
            }

            $this->command->info('Default CMS settings created.');

            // Create default CMS menus (4 locations)
            $menuDefinitions = [
                'header' => [
                    'name' => 'Cabeçalho',
                    'slug' => 'header',
                    'description' => 'Menu principal do cabeçalho (desktop)',
                    'items' => [
                        ['title' => 'Início', 'url' => '/', 'sort_order' => 0],
                        ['title' => 'Sobre', 'url' => '/sobre', 'sort_order' => 1],
                        ['title' => 'Projetos', 'url' => '/projetos', 'sort_order' => 2],
                        ['title' => 'ODS 2030', 'url' => '/ods', 'sort_order' => 3],
                        ['title' => 'Notícias', 'url' => '/noticias', 'sort_order' => 4],
                        ['title' => 'Galeria', 'url' => '/galeria', 'sort_order' => 5],
                        ['title' => 'Transparência', 'url' => '/transparencia', 'sort_order' => 6],
                        ['title' => 'Contato', 'url' => '/contato', 'sort_order' => 7, 'css_class' => 'btn-primary'],
                    ],
                ],
                'sidebar' => [
                    'name' => 'Sidebar Mobile',
                    'slug' => 'sidebar',
                    'description' => 'Menu lateral mobile (drawer)',
                    'items' => [
                        ['title' => 'Início', 'url' => '/', 'sort_order' => 0],
                        ['title' => 'Sobre o ISSM', 'url' => '/sobre', 'sort_order' => 1],
                        ['title' => 'Projetos', 'url' => '/projetos', 'sort_order' => 2],
                        ['title' => 'ODS 2030', 'url' => '/ods', 'sort_order' => 3],
                        ['title' => 'Notícias', 'url' => '/noticias', 'sort_order' => 4],
                        ['title' => 'Galeria', 'url' => '/galeria', 'sort_order' => 5],
                        ['title' => 'Transparência', 'url' => '/transparencia', 'sort_order' => 6],
                        ['title' => 'Fale Conosco', 'url' => '/contato', 'sort_order' => 7, 'css_class' => 'btn-cta'],
                    ],
                ],
                'bottom' => [
                    'name' => 'Barra Inferior',
                    'slug' => 'bottom',
                    'description' => 'Barra de navegação inferior (mobile)',
                    'items' => [
                        ['title' => 'Início', 'url' => '/', 'sort_order' => 0],
                        ['title' => 'Projetos', 'url' => '/projetos', 'sort_order' => 1],
                        ['title' => 'Notícias', 'url' => '/noticias', 'sort_order' => 3],
                    ],
                ],
                'footer' => [
                    'name' => 'Rodapé',
                    'slug' => 'footer',
                    'description' => 'Links rápidos do rodapé',
                    'items' => [
                        ['title' => 'Sobre o ISSM', 'url' => '/sobre', 'sort_order' => 0],
                        ['title' => 'Nossos Projetos', 'url' => '/projetos', 'sort_order' => 1],
                        ['title' => 'ODS 2030', 'url' => '/ods', 'sort_order' => 2],
                        ['title' => 'Notícias', 'url' => '/noticias', 'sort_order' => 3],
                        ['title' => 'Galeria', 'url' => '/galeria', 'sort_order' => 4],
                        ['title' => 'Transparência', 'url' => '/transparencia', 'sort_order' => 5],
                        ['title' => 'Nossa Equipe', 'url' => '/sobre#equipe', 'sort_order' => 6],
                        ['title' => 'Contato', 'url' => '/contato', 'sort_order' => 7],
                    ],
                ],
            ];

            foreach ($menuDefinitions as $location => $def) {
                $existing = DB::table('cms_menus')->where('slug', $def['slug'])->first();
                if (!$existing) {
                    $menuId = DB::table('cms_menus')->insertGetId([
                        'name' => $def['name'],
                        'slug' => $def['slug'],
                        'location' => $location,
                        'description' => $def['description'],
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    foreach ($def['items'] as $item) {
                        $insertData = [
                            'cms_menu_id' => $menuId,
                            'title' => $item['title'],
                            'url' => $item['url'],
                            'sort_order' => $item['sort_order'],
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        if (isset($item['css_class'])) {
                            $insertData['css_class'] = $item['css_class'];
                        }
                        DB::table('cms_menu_items')->insert($insertData);
                    }

                    $this->command->info("CMS menu '{$def['name']}' ({$location}) created with " . count($def['items']) . ' items.');
                } else {
                    $this->command->info("CMS menu '{$def['name']}' ({$location}) already exists, skipping.');
                }
            }

            // Create default CMS pages for each public route
            $pages = [
                ['title' => 'Início', 'slug' => 'inicio', 'content' => 'Página inicial gerenciável via CMS.'],
                ['title' => 'Sobre', 'slug' => 'sobre', 'content' => 'Página institucional gerenciável via CMS.'],
                ['title' => 'ODS 2030', 'slug' => 'ods', 'content' => 'Página ODS gerenciável via CMS.'],
                ['title' => 'Galeria', 'slug' => 'galeria', 'content' => 'Página de galeria gerenciável via CMS.'],
                ['title' => 'Notícias', 'slug' => 'noticias', 'content' => 'Página de notícias gerenciável via CMS.'],
                ['title' => 'Projetos', 'slug' => 'projetos', 'content' => 'Página de projetos gerenciável via CMS.'],
                ['title' => 'Contato', 'slug' => 'contato', 'content' => 'Página de contato gerenciável via CMS.'],
                ['title' => 'Transparência', 'slug' => 'transparencia', 'content' => 'Portal da transparência gerenciável via CMS.'],
            ];

            foreach ($pages as $page) {
                $existing = DB::table('cms_pages')->where('slug', $page['slug'])->first();
                if (!$existing) {
                    DB::table('cms_pages')->insert([
                        'title' => $page['title'],
                        'slug' => $page['slug'],
                        'content' => $page['content'],
                        'status' => 'published',
                        'is_active' => true,
                        'template' => 'default',
                        'layout' => 'default',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $this->command->info("CMS page '{$page['title']}' created.");
                } else {
                    $this->command->info("CMS page '{$page['title']}' already exists, skipping.");
                }
            }

            // Create default blocks config
            $blocksConfig = [
                'cms_blocks_enabled' => '1',
                'cms_blocks_per_page' => '20',
                'cms_editor' => 'tinymce',
            ];

            foreach ($blocksConfig as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
                );
            }

            $this->command->info('Default blocks configuration created.');
        } catch (\Exception $e) {
            $this->command->error('Error creating CMS defaults: ' . $e->getMessage());
        }
    }
}
