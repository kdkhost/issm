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
        if (!Schema::hasTable('cms_audit_logs')) {
            Schema::create('cms_audit_logs', function (Blueprint $table) {
                $table->engine('InnoDB');

                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name', 255)->nullable();
                $table->string('user_email', 255)->nullable();
                $table->string('action', 100);
                $table->string('module', 100);
                $table->string('model_type', 255)->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->text('description')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('url', 255)->nullable();
                $table->string('method', 10)->nullable();
                $table->float('duration')->nullable();
                $table->timestamp('created_at')->nullable();

                $table->index('user_id');
                $table->index('action');
                $table->index('module');
                $table->index('model_type');
                $table->index('model_id');
                $table->index('created_at');
            });
        }

        if (Schema::hasTable('cms_audit_logs')) {
            if (!Schema::hasColumn('cms_audit_logs', 'user_id')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'user_name')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->string('user_name', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'user_email')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->string('user_email', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'action')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->string('action', 100);
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'module')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->string('module', 100);
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'model_type')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->string('model_type', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'model_id')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->unsignedBigInteger('model_id')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'description')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->text('description')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'old_values')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->json('old_values')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'new_values')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->json('new_values')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'ip_address')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->string('ip_address', 45)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'user_agent')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->text('user_agent')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'url')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->string('url', 255)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'method')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->string('method', 10)->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'duration')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->float('duration')->nullable();
                });
            }
            if (!Schema::hasColumn('cms_audit_logs', 'created_at')) {
                Schema::table('cms_audit_logs', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};
