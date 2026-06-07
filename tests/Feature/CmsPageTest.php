<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\User;
use Database\Seeders\CmsPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CmsPageTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CmsPermissionSeeder::class);

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $this->admin->assignRole($adminRole);
    }

    public function test_admin_can_access_cms_pages_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/pages');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_cms_pages(): void
    {
        $response = $this->get('/admin/pages');

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_cms_page(): void
    {
        $pageData = [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'status' => 'draft',
            'content' => 'Test content',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/pages', $pageData);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('cms_pages', [
            'slug' => 'test-page',
            'title' => 'Test Page',
        ]);
    }

    public function test_admin_can_edit_cms_page(): void
    {
        $page = CmsPage::create([
            'title' => 'Original Title',
            'slug' => 'original-slug',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/pages/{$page->id}", [
            'title' => 'Updated Title',
            'slug' => 'original-slug',
            'status' => 'published',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('cms_pages', [
            'id' => $page->id,
            'title' => 'Updated Title',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_delete_cms_page(): void
    {
        $page = CmsPage::create([
            'title' => 'Page to Delete',
            'slug' => 'page-to-delete',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/pages/{$page->id}");

        $response->assertSessionHas('success');
        $this->assertSoftDeleted($page);
    }

    public function test_cms_page_slug_is_unique(): void
    {
        CmsPage::create([
            'title' => 'First Page',
            'slug' => 'duplicate-slug',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/pages', [
            'title' => 'Second Page',
            'slug' => 'duplicate-slug',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('slug');
    }
}
