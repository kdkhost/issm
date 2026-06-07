<?php

namespace App\Services\Cms;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use Illuminate\Support\Facades\Cache;

class CmsCacheService
{
    protected int $ttl;

    protected const KEYS_REGISTRY = 'cms.cache_keys';

    public function __construct()
    {
        $this->ttl = (int) config('cms.cache.ttl', 3600);
    }

    public function clearPageCache(string $slug): void
    {
        Cache::forget("cms.page.{$slug}");
        Cache::forget('cms.pages.active');
        Cache::forget('cms.pages.all_slugs');
    }

    public function clearPagesCache(): void
    {
        $keys = Cache::get(self::KEYS_REGISTRY, []);
        foreach ($keys as $key => $_) {
            if (str_starts_with($key, 'cms.page.') || str_starts_with($key, 'cms.pages.')) {
                Cache::forget($key);
                unset($keys[$key]);
            }
        }
        Cache::forever(self::KEYS_REGISTRY, $keys);
    }

    public function clearSectionCache(int $pageId): void
    {
        Cache::forget("cms.sections.page.{$pageId}");
    }

    public function clearBlockCache(int $sectionId): void
    {
        Cache::forget("cms.blocks.section.{$sectionId}");
    }

    public function clearMenuCache(?string $location = null): void
    {
        if ($location) {
            Cache::forget("cms.menu.{$location}");
        } else {
            $keys = Cache::get(self::KEYS_REGISTRY, []);
            foreach ($keys as $key => $_) {
                if (str_starts_with($key, 'cms.menu.')) {
                    Cache::forget($key);
                    unset($keys[$key]);
                }
            }
            Cache::forever(self::KEYS_REGISTRY, $keys);
        }
    }

    public function clearSettingsCache(): void
    {
        Cache::forget('cms.settings');
        Cache::forget('cms.settings.all');
    }

    public function clearSeoCache(int $pageId): void
    {
        Cache::forget("cms.seo.page.{$pageId}");
        Cache::forget('cms.seo.default');
    }

    public function clearAllCmsCache(): void
    {
        $keys = Cache::get(self::KEYS_REGISTRY, []);
        foreach ($keys as $key => $_) {
            Cache::forget($key);
        }
        Cache::forget(self::KEYS_REGISTRY);
    }

    public function clearMediaCache(): void
    {
        Cache::forget('cms.media.all');
    }

    public function getCacheKey(string $key): string
    {
        return $key;
    }

    public function remember(string $key, mixed $data): mixed
    {
        return Cache::remember($key, $this->ttl, function () use ($key, $data) {
            $this->registerKey($key);
            return value($data);
        });
    }

    public function forget(string $key): void
    {
        Cache::forget($key);
        $keys = Cache::get(self::KEYS_REGISTRY, []);
        unset($keys[$key]);
        Cache::forever(self::KEYS_REGISTRY, $keys);
    }

    protected function registerKey(string $key): void
    {
        $keys = Cache::get(self::KEYS_REGISTRY, []);
        $keys[$key] = true;
        Cache::forever(self::KEYS_REGISTRY, $keys);
    }
}
