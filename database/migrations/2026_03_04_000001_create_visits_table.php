<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url', 2048)->nullable();
            $table->string('path', 512)->nullable();
            $table->string('referrer', 2048)->nullable();
            $table->string('referrer_domain', 255)->nullable();
            $table->string('source', 50)->nullable()->index();      // instagram, google, whatsapp, telegram, facebook, direct, other
            $table->string('utm_source', 255)->nullable();
            $table->string('utm_medium', 255)->nullable();
            $table->string('utm_campaign', 255)->nullable();
            $table->string('device_type', 20)->nullable();           // desktop, mobile, tablet
            $table->string('browser', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('session_id', 100)->nullable()->index();
            $table->boolean('is_bot')->default(false);
            $table->timestamps();

            $table->index('created_at');
            $table->index('path');
            $table->index('referrer_domain');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
