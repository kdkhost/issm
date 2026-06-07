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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'status', 'is_active', 'published_at', 'expires_at',
        'sort_order', 'template', 'layout', 'css_class', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(CmsSection::class, 'cms_page_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(CmsBlock::class, 'cms_page_id');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(CmsPageSeo::class, 'cms_page_id');
    }

    public function versions(): MorphMany
    {
        return $this->morphMany(CmsVersion::class, 'versionable');
    }

    public function media(): MorphToMany
    {
        return $this->morphToMany(CmsMedia::class, 'mediable');
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

    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }
}
