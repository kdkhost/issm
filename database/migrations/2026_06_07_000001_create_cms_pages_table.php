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
        if (!Schema::hasTable('cms_pages')) {
            Schema::create('cms_pages', function (Blueprint $table) {
                $table->engine('InnoDB');

                $table->bigIncrements('id');
                $table->string('title', 255);
                $table->string('slug', 200)->unique();
                $table->longText('content')->nullable();
                $table->string('status', 50)->default('draft');
                $table->boolean('is_active')->default(true);
                $table->timestamp('published_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->integer('sort_order')->default(0);
                $table->string('template', 100)->nullable();
                $table->string('layout', 100)->nullable();
                $table->string('css_class', 255)->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->index('slug');
                $table->index('status');
                $table->index('is_active');
                $table->index('published_at');
                $table->index('deleted_at');
                $table->index('sort_order');
                $table->index('created_at');
                $table->index('updated_at');
            });
        }

        if (Schema::hasTable('cms_pages')) {
            if (!Schema::hasColumn('cms_pages', 'title')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->string('title', 255);
                });
            }
            if (!Schema::hasColumn('cms_pages', 'slug')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->string('slug', 200)->unique();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'content')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->longText('content')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'status')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->string('status', 50)->default('draft');
                });
            }
            if (!Schema::hasColumn('cms_pages', 'is_active')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_pages', 'published_at')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->timestamp('published_at')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'expires_at')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->timestamp('expires_at')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'sort_order')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->integer('sort_order')->default(0);
                });
            }
            if (!Schema::hasColumn('cms_pages', 'template')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->string('template', 100)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'layout')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->string('layout', 100)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'css_class')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->string('css_class', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'created_by')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'updated_by')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->unsignedBigInteger('updated_by')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_pages', 'deleted_at')) {
                Schema::table('cms_pages', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};
