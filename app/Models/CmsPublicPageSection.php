<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPublicPageSection extends Model
{
    protected $fillable = [
        'page_id', 'section_key', 'section_label', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPublicPage::class, 'page_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(CmsPublicPageField::class, 'page_id', 'page_id')
            ->where('section_key', $this->section_key)
            ->orderBy('sort_order');
    }
}
