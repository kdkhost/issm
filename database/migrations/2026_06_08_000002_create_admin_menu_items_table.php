<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('route_name');
            $table->string('icon_svg')->nullable();
            $table->string('group_label')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_dropdown')->default(false);
            $table->json('children')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_menu_items');
    }
};
