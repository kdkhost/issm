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

            // Create default CMS menus
            $existingMenu = DB::table('cms_menus')->where('slug', 'main')->first();
            if (!$existingMenu) {
                $menuId = DB::table('cms_menus')->insertGetId([
                    'name' => 'Main Menu',
                    'slug' => 'main',
                    'description' => 'Main navigation menu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $menuItems = [
                    ['label' => 'Inicio', 'route' => 'home', 'order' => 0],
                    ['label' => 'Sobre', 'route' => 'about.index', 'order' => 1],
                    ['label' => 'Projetos', 'route' => 'projects.index', 'order' => 2],
                    ['label' => 'ODS 2030', 'route' => 'ods.index', 'order' => 3],
                    ['label' => 'Noticias', 'route' => 'news.index', 'order' => 4],
                    ['label' => 'Galeria', 'route' => 'gallery.index', 'order' => 5],
                    ['label' => 'Transparencia', 'route' => 'transparency.index', 'order' => 6],
                    ['label' => 'Contato', 'route' => 'contact.index', 'order' => 7],
                ];

                foreach ($menuItems as $item) {
                    DB::table('cms_menu_items')->insert([
                        'cms_menu_id' => $menuId,
                        'title' => $item['label'],
                        'route' => $item['route'],
                        'sort_order' => $item['order'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $this->command->info('Default CMS menu created with ' . count($menuItems) . ' items.');
            } else {
                $this->command->info('Main menu already exists, skipping.');
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
