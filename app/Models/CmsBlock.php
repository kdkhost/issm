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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class CmsBlock extends Model
{
    protected $fillable = [
        'cms_section_id', 'cms_page_id', 'type', 'title', 'subtitle', 'content',
        'image', 'video_url', 'link_url', 'link_text', 'link_target', 'status',
        'is_active', 'sort_order', 'settings', 'published_at', 'expires_at',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'settings' => 'array',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CmsSection::class, 'cms_section_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(CmsField::class, 'cms_block_id');
    }

    public function media(): MorphToMany
    {
        return $this->morphToMany(CmsMedia::class, 'mediable');
    }

    public function versions(): MorphMany
    {
        return $this->morphMany(CmsVersion::class, 'versionable');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}
