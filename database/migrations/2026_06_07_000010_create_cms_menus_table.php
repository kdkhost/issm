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
        if (!Schema::hasTable('cms_menus')) {
            Schema::create('cms_menus', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset('utf8mb4')->collation('utf8mb4_unicode_ci');

                $table->bigIncrements('id');
                $table->string('name', 255);
                $table->string('slug', 200)->unique();
                $table->text('description')->nullable();
                $table->string('location', 100)->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->json('settings')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index('slug');
                $table->index('location');
                $table->index('is_active');
                $table->index('sort_order');
            });
        }

        if (Schema::hasTable('cms_menus')) {
            if (!Schema::hasColumn('cms_menus', 'name')) {
                Schema::table('cms_menus', function (Blueprint $table) {
                    $table->string('name', 255);
                });
            }
            if (!Schema::hasColumn('cms_menus', 'slug')) {
                Schema::table('cms_menus', function (Blueprint $table) {
                    $table->string('slug', 200)->unique();
                });
            }
            if (!Schema::hasColumn('cms_menus', 'description')) {
                Schema::table('cms_menus', function (Blueprint $table) {
                    $table->text('description')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menus', 'location')) {
                Schema::table('cms_menus', function (Blueprint $table) {
                    $table->string('location', 100)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menus', 'is_active')) {
                Schema::table('cms_menus', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_menus', 'sort_order')) {
                Schema::table('cms_menus', function (Blueprint $table) {
                    $table->integer('sort_order')->default(0);
                });
            }
            if (!Schema::hasColumn('cms_menus', 'settings')) {
                Schema::table('cms_menus', function (Blueprint $table) {
                    $table->json('settings')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_menus', 'created_by')) {
                Schema::table('cms_menus', function (Blueprint $table) {
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
