<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPublicPageSeo extends Model
{
    protected $table = 'cms_public_page_seo';

    protected $fillable = [
        'page_id', 'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'og_image', 'canonical_url', 'robots_meta',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPublicPage::class, 'page_id');
    }
}
