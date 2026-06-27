<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'event_location',
        'cover_image',
        'ideal_image_width',
        'ideal_image_height',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'ideal_image_width' => 'integer',
        'ideal_image_height' => 'integer',
        'sort_order' => 'integer',
        'active' => 'boolean',
    ];

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activePhotos()
    {
        return $this->hasMany(GalleryPhoto::class)
            ->where('active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'gallery_album_project')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('sort_order')->orderByDesc('event_date')->orderByDesc('id');
    }

    public function coverImagePath(): ?string
    {
        if ($this->cover_image) {
            return $this->cover_image;
        }

        $activePhoto = $this->relationLoaded('activePhotos')
            ? $this->activePhotos->first()
            : $this->activePhotos()->first();

        if ($activePhoto) {
            return $activePhoto->image;
        }

        $photo = $this->relationLoaded('photos')
            ? $this->photos->first()
            : $this->photos()->first();

        return optional($photo)->image;
    }

    protected static function booted(): void
    {
        static::saving(function (GalleryAlbum $album) {
            if (! $album->slug) {
                $album->slug = static::makeUniqueSlug($album->title, $album->id);
            }
        });
    }

    public static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'album';
        $slug = $base;
        $counter = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
