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
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CmsPermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $permissions = [
                // Pages
                'cms.pages.view', 'cms.pages.create', 'cms.pages.edit', 'cms.pages.delete', 'cms.pages.publish',
                // Sections
                'cms.sections.view', 'cms.sections.create', 'cms.sections.edit', 'cms.sections.delete',
                // Blocks
                'cms.blocks.view', 'cms.blocks.create', 'cms.blocks.edit', 'cms.blocks.delete',
                // Media
                'cms.media.view', 'cms.media.upload', 'cms.media.edit', 'cms.media.delete',
                // SEO
                'cms.seo.view', 'cms.seo.edit', 'cms.seo.update',
                // Audit
                'cms.audit.view',
                // Settings
                'cms.settings.view', 'cms.settings.edit',
                // Cache
                'cms.cache.view', 'cms.cache.clear',
                // Versions
                'cms.versions.view', 'cms.versions.restore',
                // Menus
                'cms.menus.view', 'cms.menus.create', 'cms.menus.edit', 'cms.menus.delete',
            ];

            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate(['name' => $permissionName]);
            }

            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            $adminRole->givePermissionTo($permissions);

            // Assign admin role to all existing admin users
            $adminUsers = \App\Models\User::where('is_admin', true)->whereDoesntHave('roles')->get();
            foreach ($adminUsers as $user) {
                $user->assignRole('admin');
            }

            $count = count($permissions);
            $assigned = $adminUsers->count();
            $this->command->info("CMS permissions created successfully. Total: {$count}");
            if ($assigned > 0) {
                $this->command->info("Admin role assigned to {$assigned} existing user(s).");
            }
        } catch (\Exception $e) {
            $this->command->error('Error creating CMS permissions: ' . $e->getMessage());
        }
    }
}
