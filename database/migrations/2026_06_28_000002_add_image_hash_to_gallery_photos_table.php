<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->string('image_hash', 64)->nullable()->after('image')->index();
        });

        DB::table('gallery_photos')
            ->whereNull('image_hash')
            ->orderBy('id')
            ->select(['id', 'image'])
            ->chunkById(100, function ($photos) {
                foreach ($photos as $photo) {
                    $path = Storage::disk('public')->path($photo->image);

                    if (! is_file($path)) {
                        continue;
                    }

                    DB::table('gallery_photos')
                        ->where('id', $photo->id)
                        ->update(['image_hash' => hash_file('sha256', $path)]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->dropIndex(['image_hash']);
            $table->dropColumn('image_hash');
        });
    }
};
