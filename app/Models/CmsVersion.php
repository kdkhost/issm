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
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CmsVersion extends Model
{
    protected $fillable = [
        'versionable_type', 'versionable_id', 'version_number', 'title', 'content',
        'data', 'summary', 'user_id', 'ip_address', 'user_agent', 'created_by',
    ];

    protected $casts = [
        'data' => 'array',
        'version_number' => 'integer',
    ];

    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('version_number');
    }
}
