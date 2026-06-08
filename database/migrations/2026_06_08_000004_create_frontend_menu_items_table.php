<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('route_name');
            $table->text('icon_svg')->nullable();
            $table->string('icon_bg_color')->nullable();
            $table->string('icon_color')->nullable();
            $table->boolean('is_button')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_menu_items');
    }
};
