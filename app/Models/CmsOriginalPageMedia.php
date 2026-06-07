<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsOriginalPageMedia extends Model
{
    protected $table = 'cms_original_page_media';

    protected $fillable = [
        'page_id', 'section_key', 'media_key', 'media_url', 'media_alt', 'sort_order',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsOriginalPage::class, 'page_id');
    }
}
