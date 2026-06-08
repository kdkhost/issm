<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransparencyDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'google_drive_file_id',
        'google_drive_url',
        'source',
        'category',
        'category_id',
        'year',
        'published_at',
        'active'
    ];

    protected $casts = [
        'published_at' => 'date',
        'active' => 'boolean'
    ];

    public function categoryModel()
    {
        return $this->belongsTo(TransparencyCategory::class, 'category_id');
    }
}
