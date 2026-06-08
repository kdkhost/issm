<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_public_pages', function (Blueprint $table) {
            $table->longText('custom_html')->nullable()->after('is_active');
            $table->boolean('use_custom_html')->default(false)->after('custom_html');
        });
    }

    public function down(): void
    {
        Schema::table('cms_public_pages', function (Blueprint $table) {
            $table->dropColumn(['custom_html', 'use_custom_html']);
        });
    }
};
