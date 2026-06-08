<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransparencyCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'google_drive_folder_id', 'sort_order', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(TransparencyDocument::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
