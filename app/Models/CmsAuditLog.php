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

class CmsAuditLog extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'user_email', 'action', 'module', 'model_type',
        'model_id', 'description', 'old_values', 'new_values', 'ip_address',
        'user_agent', 'url', 'method', 'duration',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'duration' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
