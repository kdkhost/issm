<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsOriginalPage extends Model
{
    use SoftDeletes;

    protected $table = 'cms_original_pages';

    protected $fillable = [
        'route_name', 'route_uri', 'controller', 'method', 'view_path',
        'page_key', 'title', 'admin_label', 'is_editable', 'is_active',
        'sort_order', 'seo_enabled', 'cache_enabled', 'needs_review', 'last_mapped_at',
    ];

    protected $casts = [
        'is_editable' => 'boolean',
        'is_active' => 'boolean',
        'seo_enabled' => 'boolean',
        'cache_enabled' => 'boolean',
        'needs_review' => 'boolean',
        'last_mapped_at' => 'datetime',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(CmsOriginalPageSection::class, 'page_id')->orderBy('sort_order');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(CmsOriginalPageField::class, 'page_id')->orderBy('sort_order');
    }

    public function media(): HasMany
    {
        return $this->hasMany(CmsOriginalPageMedia::class, 'page_id')->orderBy('sort_order');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(CmsOriginalPageSeo::class, 'page_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(CmsOriginalPageVersion::class, 'page_id')->latest();
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(CmsOriginalPageAuditLog::class, 'page_id')->latest();
    }

    public function mappedFieldsCount(): int
    {
        return $this->fields()->count();
    }

    public function pendingFieldsCount(): int
    {
        return $this->fields()->where(function ($query) {
            $query->whereNull('field_value')->orWhere('field_value', '');
        })->count();
    }

    public function publicUrl(): ?string
    {
        if ($this->route_name) {
            try {
                return route($this->route_name);
            } catch (\Throwable) {
                // Fall back for routes like /pagina/{slug}
            }
        }

        if ($this->route_uri) {
            $uri = $this->route_uri;

            if (str_contains($uri, '{slug}') && !empty($this->page_key)) {
                $uri = str_replace('{slug}', $this->page_key, $uri);
            }

            return url($uri);
        }

        return null;
    }
}
