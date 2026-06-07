<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cms_pages') && !Schema::hasColumn('cms_pages', 'settings')) {
            Schema::table('cms_pages', function (Blueprint $table) {
                $table->json('settings')->nullable()->after('css_class');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cms_pages') && Schema::hasColumn('cms_pages', 'settings')) {
            Schema::table('cms_pages', function (Blueprint $table) {
                $table->dropColumn('settings');
            });
        }
    }
};
