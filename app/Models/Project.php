<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'image', 'category', 'ods_goals', 'status', 'start_date', 'end_date', 'location', 'featured', 'active', 'order'];

    protected $casts = [
        'ods_goals' => 'array',
        'featured' => 'boolean',
        'active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
