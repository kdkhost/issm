<?php

namespace App\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_sections')) {
            Schema::create('cms_sections', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset('utf8mb4')->collation('utf8mb4_unicode_ci');

                $table->bigIncrements('id');
                $table->unsignedBigInteger('cms_page_id');
                $table->string('title', 255);
                $table->string('slug', 200)->nullable();
                $table->text('description')->nullable();
                $table->string('status', 50)->default('active');
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->string('css_class', 255)->nullable();
                $table->json('settings')->nullable();
                $table->timestamps();

                $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
                $table->index('cms_page_id');
                $table->index('slug');
                $table->index('status');
                $table->index('is_active');
                $table->index('sort_order');
            });
        }

        if (Schema::hasTable('cms_sections')) {
            if (!Schema::hasColumn('cms_sections', 'cms_page_id')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->unsignedBigInteger('cms_page_id');
                    $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
                });
            }
            if (!Schema::hasColumn('cms_sections', 'title')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->string('title', 255);
                });
            }
            if (!Schema::hasColumn('cms_sections', 'slug')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->string('slug', 200)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_sections', 'description')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->text('description')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_sections', 'status')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->string('status', 50)->default('active');
                });
            }
            if (!Schema::hasColumn('cms_sections', 'is_active')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_sections', 'sort_order')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->integer('sort_order')->default(0);
                });
            }
            if (!Schema::hasColumn('cms_sections', 'css_class')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->string('css_class', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_sections', 'settings')) {
                Schema::table('cms_sections', function (Blueprint $table) {
                    $table->json('settings')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};
