<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('event_date')->nullable();
            $table->string('event_location')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedInteger('ideal_image_width')->default(1600);
            $table->unsignedInteger('ideal_image_height')->default(1200);
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_album_id')->constrained('gallery_albums')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('size_kb')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('gallery_album_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_album_id')->constrained('gallery_albums')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['gallery_album_id', 'project_id']);
        });

        $this->migrateLegacyGalleryRows();
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_album_project');
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('gallery_albums');
    }

    private function migrateLegacyGalleryRows(): void
    {
        if (! Schema::hasTable('gallery')) {
            return;
        }

        $legacyItems = DB::table('gallery')->orderBy('order')->orderBy('id')->get();

        if ($legacyItems->isEmpty()) {
            return;
        }

        $albumIds = [];
        $albumOrder = 0;

        foreach ($legacyItems as $item) {
            $albumTitle = trim((string) ($item->album ?: 'Galeria'));
            $albumKey = mb_strtolower($albumTitle);

            if (! isset($albumIds[$albumKey])) {
                $albumIds[$albumKey] = DB::table('gallery_albums')->insertGetId([
                    'title' => $albumTitle,
                    'slug' => $this->uniqueSlug($albumTitle),
                    'description' => null,
                    'event_date' => null,
                    'event_location' => null,
                    'cover_image' => null,
                    'ideal_image_width' => 1600,
                    'ideal_image_height' => 1200,
                    'sort_order' => $albumOrder++,
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('gallery_photos')->insert([
                'gallery_album_id' => $albumIds[$albumKey],
                'title' => $item->title ?: $albumTitle,
                'description' => $item->description,
                'image' => $item->image,
                'width' => null,
                'height' => null,
                'size_kb' => null,
                'sort_order' => (int) ($item->order ?? 0),
                'active' => (bool) ($item->active ?? true),
                'created_at' => $item->created_at ?? now(),
                'updated_at' => $item->updated_at ?? now(),
            ]);
        }
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'album';
        $slug = $base;
        $counter = 2;

        while (DB::table('gallery_albums')->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
};
