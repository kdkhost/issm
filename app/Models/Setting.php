<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 300, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    public static function isMaintenanceMode(): bool
    {
        return (bool) static::get('maintenance_mode', false);
    }

    public static function uploadLimitMb(string $type = 'image', int $default = null): int
    {
        $key = $type === 'video' ? 'global_video_max_upload_mb' : 'global_image_max_upload_mb';
        $fallback = $default ?? ($type === 'video' ? 50 : 5);

        return max(1, min((int) static::get($key, $fallback), 512));
    }

    public static function uploadLimitKb(string $type = 'image', int $default = null): int
    {
        return static::uploadLimitMb($type, $default) * 1024;
    }
}
