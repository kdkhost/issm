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
        if (!Schema::hasTable('cms_redirects')) {
            Schema::create('cms_redirects', function (Blueprint $table) {
                $table->engine('InnoDB');

                $table->bigIncrements('id');
                $table->string('from_url', 255);
                $table->string('to_url', 255);
                $table->integer('status_code')->default(301);
                $table->boolean('is_active')->default(true);
                $table->integer('hit_count')->default(0);
                $table->timestamp('last_hit_at')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->index('from_url');
                $table->index('to_url');
                $table->index('status_code');
                $table->index('is_active');
            });
        }

        if (Schema::hasTable('cms_redirects')) {
            if (!Schema::hasColumn('cms_redirects', 'from_url')) {
                Schema::table('cms_redirects', function (Blueprint $table) {
                    $table->string('from_url', 255);
                });
            }
            if (!Schema::hasColumn('cms_redirects', 'to_url')) {
                Schema::table('cms_redirects', function (Blueprint $table) {
                    $table->string('to_url', 255);
                });
            }
            if (!Schema::hasColumn('cms_redirects', 'status_code')) {
                Schema::table('cms_redirects', function (Blueprint $table) {
                    $table->integer('status_code')->default(301);
                });
            }
            if (!Schema::hasColumn('cms_redirects', 'is_active')) {
                Schema::table('cms_redirects', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }
            if (!Schema::hasColumn('cms_redirects', 'hit_count')) {
                Schema::table('cms_redirects', function (Blueprint $table) {
                    $table->integer('hit_count')->default(0);
                });
            }
            if (!Schema::hasColumn('cms_redirects', 'last_hit_at')) {
                Schema::table('cms_redirects', function (Blueprint $table) {
                    $table->timestamp('last_hit_at')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_redirects', 'created_by')) {
                Schema::table('cms_redirects', function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_redirects', 'deleted_at')) {
                Schema::table('cms_redirects', function (Blueprint $table) {
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
