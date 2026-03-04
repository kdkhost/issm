<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('linkedin');
            $table->string('phone_support')->nullable()->after('whatsapp');
            $table->boolean('support_active')->default(false)->after('phone_support');
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'phone_support', 'support_active']);
        });
    }
};
