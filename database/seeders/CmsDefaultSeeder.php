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
