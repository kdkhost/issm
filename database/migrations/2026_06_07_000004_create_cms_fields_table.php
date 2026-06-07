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
        if (!Schema::hasTable('cms_fields')) {
            Schema::create('cms_fields', function (Blueprint $table) {
                $table->engine('InnoDB');

                $table->bigIncrements('id');
                $table->unsignedBigInteger('cms_block_id');
                $table->string('name', 255);
                $table->string('key', 200);
                $table->string('type', 50)->default('text');
                $table->text('value')->nullable();
                $table->json('settings')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('cms_block_id')->references('id')->on('cms_blocks')->onDelete('cascade');
                $table->index('cms_block_id');
                $table->index('key');
                $table->index('is_active');
                $table->index('sort_order');
            });
        }

        if (Schema::hasTable('cms_fields')) {
            if (!Schema::hasColumn('cms_fields', 'cms_block_id')) {
                Schema::table('cms_fields', function (Blueprint $table) {
                    $table->unsignedBigInteger('cms_block_id');
                    $table->foreign('cms_block_id')->references('id')->on('cms_blocks')->onDelete('cascade');
                });
            }
            if (!Schema::hasColumn('cms_fields', 'name')) {
                Schema::table('cms_fields', function (Blueprint $table) {
                    $table->string('name', 255);
                });
            }
            if (!Schema::hasColumn('cms_fields', 'key')) {
                Schema::table('cms_fields', function (Blueprint $table) {
                    $table->string('key', 200);
                });
            }
            if (!Schema::hasColumn('cms_fields', 'type')) {
                Schema::table('cms_fields', function (Blueprint $table) {
                    $table->string('type', 50)->default('text');
                });
            }
            if (!Schema::hasColumn('cms_fields', 'value')) {
                Schema::table('cms_fields', function (Blueprint $table) {
                    $table->text('value')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_fields', 'settings')) {
                Schema::table('cms_fields', function (Blueprint $table) {
                    $table->json('settings')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_fields', 'is_active')) {
                Schema::table('cms_fields', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_fields', 'sort_order')) {
                Schema::table('cms_fields', function (Blueprint $table) {
                    $table->integer('sort_order')->default(0);
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};
