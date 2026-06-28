<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectSupportType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'instructions',
        'suggested_amounts',
        'requires_amount',
        'requires_quantity',
        'requires_address',
        'requires_document',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'suggested_amounts' => 'array',
        'requires_amount' => 'boolean',
        'requires_quantity' => 'boolean',
        'requires_address' => 'boolean',
        'requires_document' => 'boolean',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function requests()
    {
        return $this->hasMany(ProjectSupportRequest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('sort_order')->orderBy('name');
    }

    protected static function booted(): void
    {
        static::saving(function (self $type) {
            if (! $type->slug) {
                $type->slug = Str::slug($type->name);
            }
        });
    }
}
