<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsOriginalPageSection extends Model
{
    protected $table = 'cms_original_page_sections';

    protected $fillable = [
        'page_id', 'section_key', 'section_label', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsOriginalPage::class, 'page_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(CmsOriginalPageField::class, 'page_id', 'page_id')
            ->where('section_key', $this->section_key)
            ->orderBy('sort_order');
    }
}
