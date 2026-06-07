<?php

namespace Tests\Feature;

use Database\Seeders\CmsPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CmsPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected array $expectedPermissions = [
        'cms.pages.view', 'cms.pages.create', 'cms.pages.edit', 'cms.pages.delete', 'cms.pages.publish',
        'cms.sections.view', 'cms.sections.create', 'cms.sections.edit', 'cms.sections.delete',
        'cms.blocks.view', 'cms.blocks.create', 'cms.blocks.edit', 'cms.blocks.delete',
        'cms.media.view', 'cms.media.upload', 'cms.media.edit', 'cms.media.delete',
        'cms.seo.view', 'cms.seo.edit',
        'cms.audit.view',
        'cms.settings.view', 'cms.settings.edit',
        'cms.cache.clear',
        'cms.versions.view', 'cms.versions.restore',
        'cms.menus.view', 'cms.menus.create', 'cms.menus.edit', 'cms.menus.delete',
    ];

    public function test_permissions_exist_after_seeding(): void
    {
        $this->seed(CmsPermissionSeeder::class);

        foreach ($this->expectedPermissions as $permissionName) {
            $this->assertDatabaseHas('permissions', [
                'name' => $permissionName,
            ]);
        }

        $this->assertCount(count($this->expectedPermissions), Permission::all());
    }

    public function test_admin_role_has_cms_permissions(): void
    {
        $this->seed(CmsPermissionSeeder::class);

        $adminRole = Role::where('name', 'admin')->first();
        $this->assertNotNull($adminRole);

        $adminPermissions = $adminRole->permissions->pluck('name')->toArray();

        foreach ($this->expectedPermissions as $permissionName) {
            $this->assertContains($permissionName, $adminPermissions);
        }
    }
}
