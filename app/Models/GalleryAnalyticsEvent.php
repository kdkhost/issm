<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GalleryAnalyticsEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'gallery_album_id',
        'gallery_photo_id',
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'referer',
        'url',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function photo()
    {
        return $this->belongsTo(GalleryPhoto::class, 'gallery_photo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function recordFromRequest(
        Request $request,
        string $eventType,
        ?int $albumId = null,
        ?int $photoId = null,
        array $metadata = []
    ): ?self {
        try {
            return static::create([
                'event_type' => $eventType,
                'gallery_album_id' => $albumId,
                'gallery_photo_id' => $photoId,
                'user_id' => optional($request->user())->id,
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'referer' => (string) $request->headers->get('referer'),
                'url' => (string) $request->fullUrl(),
                'metadata' => array_filter($metadata, fn ($value) => $value !== null && $value !== ''),
                'occurred_at' => now(),
            ]);
        } catch (\Throwable) {
            return null;
        }
    }
}
