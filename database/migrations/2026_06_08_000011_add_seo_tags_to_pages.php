<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->text('seo_tags')->nullable()->after('meta_description');
        });

        Schema::table('cms_public_page_seo', function (Blueprint $table) {
            $table->text('seo_tags')->nullable()->after('meta_description');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('seo_tags');
        });

        Schema::table('cms_public_page_seo', function (Blueprint $table) {
            $table->dropColumn('seo_tags');
        });
    }
};
