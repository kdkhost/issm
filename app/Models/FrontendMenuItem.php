<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendMenuItem extends Model
{
    protected $fillable = [
        'label', 'route_name', 'icon_svg', 'icon_bg_color',
        'icon_color', 'is_button', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_button' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function getOrdered(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }
}
