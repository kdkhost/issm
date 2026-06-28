<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 40)->index();
            $table->foreignId('gallery_album_id')->nullable()->constrained('gallery_albums')->nullOnDelete();
            $table->foreignId('gallery_photo_id')->nullable()->constrained('gallery_photos')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id', 120)->nullable()->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->text('url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();

            $table->index(['gallery_album_id', 'event_type']);
            $table->index(['gallery_photo_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_analytics_events');
    }
};
