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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPageSeo extends Model
{
    protected $table = 'cms_page_seo';

    protected $fillable = [
        'cms_page_id', 'meta_title', 'meta_description', 'meta_keywords', 'slug',
        'canonical_url', 'og_title', 'og_description', 'og_image', 'og_type',
        'twitter_title', 'twitter_description', 'twitter_image', 'robots_index',
        'robots_follow', 'schema_markup', 'sitemap_priority', 'sitemap_frequency',
        'sitemap_enabled', 'is_active',
    ];

    protected $casts = [
        'sitemap_enabled' => 'boolean',
        'is_active' => 'boolean',
        'sitemap_priority' => 'float',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }
}
