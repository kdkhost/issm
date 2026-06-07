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
        'category',
        'year',
        'published_at',
        'active'
    ];

    protected $casts = [
        'published_at' => 'date',
        'active' => 'boolean'
    ];
}
