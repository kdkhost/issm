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
        if (!Schema::hasTable('cms_blocks')) {
            Schema::create('cms_blocks', function (Blueprint $table) {
                $table->engine('InnoDB');

                $table->bigIncrements('id');
                $table->unsignedBigInteger('cms_section_id')->nullable();
                $table->unsignedBigInteger('cms_page_id')->nullable();
                $table->string('type', 100);
                $table->string('title', 255)->nullable();
                $table->string('subtitle', 255)->nullable();
                $table->longText('content')->nullable();
                $table->string('image', 255)->nullable();
                $table->string('video_url', 255)->nullable();
                $table->string('link_url', 255)->nullable();
                $table->string('link_text', 255)->nullable();
                $table->string('link_target', 20)->default('_self');
                $table->string('status', 50)->default('active');
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->json('settings')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('cms_section_id')->references('id')->on('cms_sections')->onDelete('set null');
                $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('set null');
                $table->index('cms_section_id');
                $table->index('cms_page_id');
                $table->index('type');
                $table->index('status');
                $table->index('is_active');
                $table->index('sort_order');
                $table->index('published_at');
            });
        }

        if (Schema::hasTable('cms_blocks')) {
            if (!Schema::hasColumn('cms_blocks', 'cms_section_id')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->unsignedBigInteger('cms_section_id')->nullable();
                    $table->foreign('cms_section_id')->references('id')->on('cms_sections')->onDelete('set null');
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'cms_page_id')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->unsignedBigInteger('cms_page_id')->nullable();
                    $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('set null');
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'type')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('type', 100);
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'title')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('title', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'subtitle')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('subtitle', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'content')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->longText('content')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'image')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('image', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'video_url')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('video_url', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'link_url')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('link_url', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'link_text')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('link_text', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'link_target')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('link_target', 20)->default('_self');
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'status')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->string('status', 50)->default('active');
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'is_active')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'sort_order')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->integer('sort_order')->default(0);
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'settings')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->json('settings')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'published_at')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->timestamp('published_at')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'expires_at')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->timestamp('expires_at')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'created_by')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_blocks', 'updated_by')) {
                Schema::table('cms_blocks', function (Blueprint $table) {
                    $table->unsignedBigInteger('updated_by')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};
