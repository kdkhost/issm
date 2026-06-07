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
        if (!Schema::hasTable('cms_menu_items')) {
            Schema::create('cms_menu_items', function (Blueprint $table) {
                $table->engine('InnoDB');

                $table->bigIncrements('id');
                $table->unsignedBigInteger('cms_menu_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('title', 255);
                $table->string('url', 255)->nullable();
                $table->string('route', 255)->nullable();
                $table->string('icon', 100)->nullable();
                $table->string('target', 20)->default('_self');
                $table->json('params')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->string('css_class', 255)->nullable();
                $table->string('rel', 100)->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->foreign('cms_menu_id')->references('id')->on('cms_menus')->onDelete('cascade');
                $table->foreign('parent_id')->references('id')->on('cms_menu_items')->onDelete('set null');
                $table->index('cms_menu_id');
                $table->index('parent_id');
                $table->index('is_active');
                $table->index('sort_order');
            });
        }

        if (Schema::hasTable('cms_menu_items')) {
            if (!Schema::hasColumn('cms_menu_items', 'cms_menu_id')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->unsignedBigInteger('cms_menu_id');
                    $table->foreign('cms_menu_id')->references('id')->on('cms_menus')->onDelete('cascade');
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'parent_id')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->unsignedBigInteger('parent_id')->nullable();
                    $table->foreign('parent_id')->references('id')->on('cms_menu_items')->onDelete('set null');
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'title')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->string('title', 255);
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'url')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->string('url', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'route')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->string('route', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'icon')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->string('icon', 100)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'target')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->string('target', 20)->default('_self');
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'params')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->json('params')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'is_active')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'sort_order')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->integer('sort_order')->default(0);
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'css_class')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->string('css_class', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'rel')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->string('rel', 100)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menu_items', 'created_by')) {
                Schema::table('cms_menu_items', function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};
