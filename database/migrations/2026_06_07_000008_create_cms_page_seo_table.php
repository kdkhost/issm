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
        if (!Schema::hasTable('cms_page_seo')) {
            Schema::create('cms_page_seo', function (Blueprint $table) {
                $table->engine('InnoDB');

                $table->bigIncrements('id');
                $table->unsignedBigInteger('cms_page_id')->unique();
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->string('meta_keywords', 255)->nullable();
                $table->string('slug', 200)->nullable();
                $table->string('canonical_url', 255)->nullable();
                $table->string('og_title', 255)->nullable();
                $table->text('og_description')->nullable();
                $table->string('og_image', 255)->nullable();
                $table->string('og_type', 50)->default('website');
                $table->string('twitter_title', 255)->nullable();
                $table->text('twitter_description')->nullable();
                $table->string('twitter_image', 255)->nullable();
                $table->boolean('robots_index')->default(true);
                $table->boolean('robots_follow')->default(true);
                $table->json('schema_markup')->nullable();
                $table->decimal('sitemap_priority', 3, 1)->default(0.5);
                $table->string('sitemap_frequency', 20)->default('monthly');
                $table->boolean('sitemap_enabled')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
                $table->index('cms_page_id');
                $table->index('slug');
                $table->index('sitemap_enabled');
            });
        }

        if (Schema::hasTable('cms_page_seo')) {
            if (!Schema::hasColumn('cms_page_seo', 'cms_page_id')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->unsignedBigInteger('cms_page_id')->unique();
                    $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'meta_title')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('meta_title', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'meta_description')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->text('meta_description')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'meta_keywords')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('meta_keywords', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'slug')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('slug', 200)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'canonical_url')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('canonical_url', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'og_title')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('og_title', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'og_description')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->text('og_description')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'og_image')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('og_image', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'og_type')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('og_type', 50)->default('website');
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'twitter_title')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('twitter_title', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'twitter_description')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->text('twitter_description')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'twitter_image')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('twitter_image', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'robots_index')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->boolean('robots_index')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'robots_follow')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->boolean('robots_follow')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'schema_markup')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->json('schema_markup')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'sitemap_priority')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->decimal('sitemap_priority', 3, 1)->default(0.5);
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'sitemap_frequency')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->string('sitemap_frequency', 20)->default('monthly');
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'sitemap_enabled')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->boolean('sitemap_enabled')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_page_seo', 'is_active')) {
                Schema::table('cms_page_seo', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};
