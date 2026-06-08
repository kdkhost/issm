<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMenuItem extends Model
{
    protected $fillable = [
        'label', 'route_name', 'icon_svg', 'group_label',
        'sort_order', 'is_active', 'is_dropdown', 'children',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_dropdown' => 'boolean',
        'children' => 'array',
    ];

    public static function getOrdered(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }
}
