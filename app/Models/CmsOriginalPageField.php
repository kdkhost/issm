<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsOriginalPageField extends Model
{
    protected $table = 'cms_original_page_fields';

    protected $fillable = [
        'page_id', 'section_key', 'field_key', 'field_type', 'field_label',
        'field_value', 'default_value', 'is_required', 'is_editable',
        'sort_order', 'validation_rules', 'help_text', 'is_synced',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_editable' => 'boolean',
        'is_synced' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsOriginalPage::class, 'page_id');
    }

    public function resolvedValue(): string
    {
        $value = $this->field_value;
        if ($value !== null && $value !== '') {
            return $value;
        }

        return (string) ($this->default_value ?? '');
    }

    public function isPending(): bool
    {
        return $this->field_value === null || $this->field_value === '';
    }
}
