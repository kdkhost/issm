<?php

namespace App\Models;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CmsMedia extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'alt_text', 'caption', 'credit', 'description', 'filename',
        'original_name', 'path', 'url', 'mime_type', 'size', 'extension', 'hash',
        'disk', 'folder', 'width', 'height', 'thumbnail_path', 'user_id',
        'status', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pages(): MorphToMany
    {
        return $this->morphedByMany(CmsPage::class, 'mediable');
    }

    public function blocks(): MorphToMany
    {
        return $this->morphedByMany(CmsBlock::class, 'mediable');
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->attributes['url'] ?? null) {
            return $this->attributes['url'];
        }

        return Storage::disk($this->attributes['disk'] ?? 'public')->url($this->attributes['path'] ?? '');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->attributes['thumbnail_path'] ?? null) {
            return Storage::disk($this->attributes['disk'] ?? 'public')->url($this->attributes['thumbnail_path']);
        }

        return null;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeImages(Builder $query): Builder
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeDocuments(Builder $query): Builder
    {
        return $query->where('mime_type', 'not like', 'image/%');
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('mime_type', 'like', $type . '/%');
    }
}
