<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'status', 'reply', 'replied_at'];

    protected $casts = ['replied_at' => 'datetime'];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
