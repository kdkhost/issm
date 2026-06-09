<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('meta_title', 255)->nullable()->after('active');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords', 500)->nullable()->after('meta_description');
            $table->string('og_image', 255)->nullable()->after('meta_keywords');
            $table->string('og_title', 255)->nullable()->after('og_image');
            $table->text('og_description')->nullable()->after('og_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'og_image', 'og_title', 'og_description']);
        });
    }
};
