<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'bio', 'photo', 'email', 'linkedin', 'whatsapp', 'phone_support', 'support_active', 'order', 'active'];

    protected $casts = ['active' => 'boolean', 'support_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order');
    }
}
