<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceIp extends Model
{
    use HasFactory;

    protected $fillable = ['ip_address', 'description', 'active'];

    protected $casts = ['active' => 'boolean'];

    public static function isAllowed(string $ip): bool
    {
        return static::where('ip_address', $ip)->where('active', true)->exists();
    }
}
