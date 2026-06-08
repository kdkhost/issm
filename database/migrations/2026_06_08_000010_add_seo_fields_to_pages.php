<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('og_title')->nullable()->after('meta_description');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->string('canonical_url')->nullable()->after('og_image');
            $table->string('robots_meta')->default('index, follow')->after('canonical_url');
            $table->unsignedTinyInteger('seo_score')->default(0)->after('robots_meta');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['og_title', 'og_description', 'og_image', 'canonical_url', 'robots_meta', 'seo_score']);
        });
    }
};
