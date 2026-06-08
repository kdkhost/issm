<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transparency_documents', function (Blueprint $table) {
            $table->string('google_drive_file_id')->nullable()->after('file_path');
            $table->string('google_drive_url')->nullable()->after('google_drive_file_id');
            $table->string('source', 20)->default('local')->after('google_drive_url');
            $table->index('google_drive_file_id');
            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transparency_documents', function (Blueprint $table) {
            $table->dropIndex(['google_drive_file_id']);
            $table->dropIndex(['source']);
            $table->dropColumn(['google_drive_file_id', 'google_drive_url', 'source']);
        });
    }
};
