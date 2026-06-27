<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_album_id',
        'title',
        'description',
        'image',
        'width',
        'height',
        'size_kb',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'size_kb' => 'integer',
        'sort_order' => 'integer',
        'active' => 'boolean',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('sort_order')->orderBy('id');
    }
}
