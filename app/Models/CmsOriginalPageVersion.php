<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsOriginalPageVersion extends Model
{
    protected $table = 'cms_original_page_versions';

    protected $fillable = [
        'page_id', 'user_id', 'snapshot', 'change_summary',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsOriginalPage::class, 'page_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
