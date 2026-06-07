<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPublicPageMedia extends Model
{
    protected $fillable = [
        'page_id', 'section_key', 'field_key', 'file_path',
        'file_type', 'alt_text', 'file_size', 'sort_order',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPublicPage::class, 'page_id');
    }
}
