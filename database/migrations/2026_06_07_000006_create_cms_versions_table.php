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
        if (!Schema::hasTable('cms_versions')) {
            Schema::create('cms_versions', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset('utf8mb4')->collation('utf8mb4_unicode_ci');

                $table->bigIncrements('id');
                $table->string('versionable_type', 255);
                $table->unsignedBigInteger('versionable_id');
                $table->integer('version_number');
                $table->string('title', 255)->nullable();
                $table->longText('content')->nullable();
                $table->json('data')->nullable();
                $table->string('summary', 255)->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index('versionable_type');
                $table->index('versionable_id');
                $table->index('version_number');
                $table->index('user_id');
            });
        }

        if (Schema::hasTable('cms_versions')) {
            if (!Schema::hasColumn('cms_versions', 'versionable_type')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->string('versionable_type', 255);
                });
            }
            if (!Schema::hasColumn('cms_versions', 'versionable_id')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->unsignedBigInteger('versionable_id');
                });
            }
            if (!Schema::hasColumn('cms_versions', 'version_number')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->integer('version_number');
                });
            }
            if (!Schema::hasColumn('cms_versions', 'title')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->string('title', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_versions', 'content')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->longText('content')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_versions', 'data')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->json('data')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_versions', 'summary')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->string('summary', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_versions', 'user_id')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_versions', 'ip_address')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->string('ip_address', 45)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_versions', 'user_agent')) {
                Schema::table('cms_versions', function (Blueprint $table) {
                    $table->text('user_agent')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_versions', 'created_by')) {
                Schema::table('cms_versions', function (Blueprint $table) {
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
