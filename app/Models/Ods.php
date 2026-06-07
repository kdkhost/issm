<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Ods extends Model
{
    use HasFactory;

    protected $table = 'ods';

    protected $fillable = ['number', 'title', 'description', 'color', 'icon', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('number');
    }

    public function getIconUrlAttribute(): ?string
    {
        if (!$this->icon) {
            return null;
        }

        $mediaPath = public_path('media/' . $this->icon);
        if (File::exists($mediaPath)) {
            return asset('media/' . $this->icon);
        }

        $storagePath = public_path('storage/' . $this->icon);
        if (File::exists($storagePath)) {
            return asset('storage/' . $this->icon);
        }

        return asset('media/' . $this->icon);
    }
}
