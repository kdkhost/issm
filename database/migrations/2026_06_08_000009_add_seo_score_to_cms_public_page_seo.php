<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_public_page_seo', function (Blueprint $table) {
            $table->unsignedTinyInteger('seo_score')->default(0)->after('robots_meta');
        });
    }

    public function down(): void
    {
        Schema::table('cms_public_page_seo', function (Blueprint $table) {
            $table->dropColumn('seo_score');
        });
    }
};
