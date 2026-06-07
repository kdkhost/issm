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
        if (!Schema::hasTable('cms_media')) {
            Schema::create('cms_media', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset('utf8mb4')->collation('utf8mb4_unicode_ci');

                $table->bigIncrements('id');
                $table->string('title', 255);
                $table->string('alt_text', 255)->nullable();
                $table->text('caption')->nullable();
                $table->string('credit', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('filename', 255);
                $table->string('original_name', 255);
                $table->string('path', 255);
                $table->string('url', 255);
                $table->string('mime_type', 100);
                $table->bigInteger('size')->default(0);
                $table->string('extension', 20);
                $table->string('hash', 64)->nullable();
                $table->string('disk', 50)->default('public');
                $table->string('folder', 255)->nullable();
                $table->integer('width')->nullable();
                $table->integer('height')->nullable();
                $table->string('thumbnail_path', 255)->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('status', 50)->default('active');
                $table->boolean('is_active')->default(true);
                $table->softDeletes();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index('mime_type');
                $table->index('extension');
                $table->index('hash');
                $table->index('user_id');
                $table->index('status');
                $table->index('is_active');
                $table->index('deleted_at');
            });
        }

        if (Schema::hasTable('cms_media')) {
            if (!Schema::hasColumn('cms_media', 'title')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('title', 255);
                });
            }
            if (!Schema::hasColumn('cms_media', 'alt_text')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('alt_text', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'caption')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->text('caption')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'credit')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('credit', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'description')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->text('description')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'filename')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('filename', 255);
                });
            }
            if (!Schema::hasColumn('cms_media', 'original_name')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('original_name', 255);
                });
            }
            if (!Schema::hasColumn('cms_media', 'path')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('path', 255);
                });
            }
            if (!Schema::hasColumn('cms_media', 'url')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('url', 255);
                });
            }
            if (!Schema::hasColumn('cms_media', 'mime_type')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('mime_type', 100);
                });
            }
            if (!Schema::hasColumn('cms_media', 'size')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->bigInteger('size')->default(0);
                });
            }
            if (!Schema::hasColumn('cms_media', 'extension')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('extension', 20);
                });
            }
            if (!Schema::hasColumn('cms_media', 'hash')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('hash', 64)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'disk')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('disk', 50)->default('public');
                });
            }
            if (!Schema::hasColumn('cms_media', 'folder')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('folder', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'width')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->integer('width')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'height')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->integer('height')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'thumbnail_path')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('thumbnail_path', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_media', 'user_id')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                });
            }
            if (!Schema::hasColumn('cms_media', 'status')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->string('status', 50)->default('active');
                });
            }
            if (!Schema::hasColumn('cms_media', 'is_active')) {
                Schema::table('cms_media', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_media', 'deleted_at')) {
                Schema::table('cms_media', function (Blueprint $table) {
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
