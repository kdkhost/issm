<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transparency_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('google_drive_folder_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('transparency_documents', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('transparency_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transparency_documents', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('transparency_categories');
    }
};
