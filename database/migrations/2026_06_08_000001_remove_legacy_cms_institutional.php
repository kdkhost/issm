<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $permissionIds = Schema::hasTable('permissions')
            ? DB::table('permissions')->where('name', 'like', 'cms.%')->pluck('id')
            : collect();

        if ($permissionIds->isNotEmpty()) {
            if (Schema::hasTable('role_has_permissions')) {
                DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            }

            if (Schema::hasTable('model_has_permissions')) {
                DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
            }

            DB::table('permissions')->whereIn('id', $permissionIds)->delete();
        }

        if (Schema::hasTable('settings')) {
            DB::table('settings')->where('key', 'like', 'cms_%')->delete();
        }

        $tables = [
            'cms_original_page_audit_logs',
            'cms_original_page_versions',
            'cms_original_page_seo',
            'cms_original_page_media',
            'cms_original_page_fields',
            'cms_original_page_sections',
            'cms_original_pages',
            'cms_menu_items',
            'cms_menus',
            'cms_redirects',
            'cms_page_seo',
            'cms_audit_logs',
            'cms_versions',
            'cms_media',
            'cms_fields',
            'cms_blocks',
            'cms_sections',
            'cms_pages',
        ];

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();

        DB::table('migrations')
            ->whereIn('migration', [
                '2026_06_07_000001_create_cms_pages_table',
                '2026_06_07_000002_create_cms_sections_table',
                '2026_06_07_000003_create_cms_blocks_table',
                '2026_06_07_000004_create_cms_fields_table',
                '2026_06_07_000005_create_cms_media_table',
                '2026_06_07_000006_create_cms_versions_table',
                '2026_06_07_000007_create_cms_audit_logs_table',
                '2026_06_07_000008_create_cms_page_seo_table',
                '2026_06_07_000009_create_cms_redirects_table',
                '2026_06_07_000010_create_cms_menus_table',
                '2026_06_07_000011_create_cms_menu_items_table',
                '2026_06_07_000012_add_settings_to_cms_pages',
                '2026_06_07_000002_create_cms_original_pages_tables',
            ])
            ->delete();
    }

    public function down(): void
    {
        // O CMS legado foi removido de forma definitiva.
    }
};
