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

use App\Models\CmsPage;
use App\Models\CmsPageSeo;
use App\Services\Cms\CmsCacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CmsSeoService
{
    protected CmsCacheService $cacheService;

    public function __construct(CmsCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getSeoForPage(int $pageId): array
    {
        $key = "cms.seo.page.{$pageId}";

        return $this->cacheService->remember($key, function () use ($pageId) {
            $page = CmsPage::find($pageId);

            if (!$page) {
                return $this->getDefaultSeo();
            }

            $seo = CmsPageSeo::where('page_id', $pageId)->first();

            return array_merge($this->getDefaultSeo(), [
                'title' => $seo->meta_title ?? $page->meta_title ?? $page->title,
                'description' => $seo->meta_description ?? $page->meta_description ?? Str::limit(strip_tags($page->content ?? ''), 160),
                'keywords' => $seo->keywords ?? '',
                'og_title' => $seo->og_title ?? $page->title,
                'og_description' => $seo->og_description ?? ($seo->meta_description ?? $page->meta_description ?? ''),
                'og_image' => $seo->og_image ?? $page->image ?? '',
                'og_type' => $seo->og_type ?? 'website',
                'canonical' => $seo->canonical ?? url()->current(),
                'noindex' => $seo->noindex ?? false,
                'nofollow' => $seo->nofollow ?? false,
                'schema_type' => $seo->schema_type ?? 'WebPage',
            ]);
        });
    }

    public function generateMetaTags(int $pageId): string
    {
        $seo = $this->getSeoForPage($pageId);
        $tags = '';

        $tags .= '<title>' . e($seo['title']) . "</title>\n";
        $tags .= '<meta name="description" content="' . e($seo['description']) . "\">\n";
        $tags .= '<meta name="keywords" content="' . e($seo['keywords']) . "\">\n";
        $tags .= '<link rel="canonical" href="' . e($seo['canonical']) . "\">\n";

        if ($seo['noindex']) {
            $tags .= '<meta name="robots" content="noindex' . ($seo['nofollow'] ? ', nofollow' : '') . "\">\n";
        } elseif ($seo['nofollow']) {
            $tags .= '<meta name="robots" content="nofollow' . "\">\n";
        }

        $tags .= '<meta property="og:title" content="' . e($seo['og_title']) . "\">\n";
        $tags .= '<meta property="og:description" content="' . e($seo['og_description']) . "\">\n";
        $tags .= '<meta property="og:type" content="' . e($seo['og_type']) . "\">\n";
        $tags .= '<meta property="og:url" content="' . e($seo['canonical']) . "\">\n";

        if ($seo['og_image']) {
            $tags .= '<meta property="og:image" content="' . e(url($seo['og_image'])) . "\">\n";
            $tags .= '<meta property="og:image:width" content=\"1200\">\n';
            $tags .= '<meta property="og:image:height" content=\"630\">\n';
        }

        $tags .= '<meta name="twitter:card" content="summary_large_image">\n';
        $tags .= '<meta name="twitter:title" content="' . e($seo['og_title']) . "\">\n";
        $tags .= '<meta name="twitter:description" content="' . e($seo['og_description']) . "\">\n";

        if ($seo['og_image']) {
            $tags .= '<meta name="twitter:image" content="' . e(url($seo['og_image'])) . "\">\n";
        }

        return $tags;
    }

    public function generateSchemaMarkup(int $pageId): string
    {
        $seo = $this->getSeoForPage($pageId);
        $page = CmsPage::find($pageId);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $seo['schema_type'],
            'name' => $seo['title'],
            'description' => $seo['description'],
            'url' => $seo['canonical'],
        ];

        if ($page && $page->image) {
            $schema['image'] = url($page->image);
        }

        $schema['inLanguage'] = app()->getLocale() ?: 'pt-BR';

        if ($page && $page->created_at) {
            $schema['datePublished'] = $page->created_at->toIso8601String();
        }

        if ($page && $page->updated_at) {
            $schema['dateModified'] = $page->updated_at->toIso8601String();
        }

        $organization = $this->getOrganizationSchema();

        return '<script type="application/ld+json">' . "\n"
            . json_encode([$schema, $organization], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            . "\n</script>";
    }

    public function getDefaultSeo(): array
    {
        $key = 'cms.seo.default';

        return $this->cacheService->remember($key, function () {
            return [
                'title' => config('app.name', 'Site'),
                'description' => '',
                'keywords' => '',
                'og_title' => config('app.name', 'Site'),
                'og_description' => '',
                'og_image' => '',
                'og_type' => 'website',
                'canonical' => url()->current(),
                'noindex' => false,
                'nofollow' => false,
                'schema_type' => 'WebPage',
            ];
        });
    }

    public function updateSeo(int $pageId, array $data): CmsPageSeo
    {
        $seo = CmsPageSeo::updateOrCreate(
            ['page_id' => $pageId],
            [
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'keywords' => $data['keywords'] ?? null,
                'og_title' => $data['og_title'] ?? null,
                'og_description' => $data['og_description'] ?? null,
                'og_image' => $data['og_image'] ?? null,
                'og_type' => $data['og_type'] ?? 'website',
                'canonical' => $data['canonical'] ?? null,
                'noindex' => $data['noindex'] ?? false,
                'nofollow' => $data['nofollow'] ?? false,
                'schema_type' => $data['schema_type'] ?? 'WebPage',
            ]
        );

        $this->cacheService->clearSeoCache($pageId);

        return $seo;
    }

    public function generateSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $xml .= $this->urlTag(url('/'), now(), '1.0', 'daily');

        $pages = CmsPage::where('status', 'published')
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($pages as $page) {
            $xml .= $this->urlTag(
                url('/' . $page->slug),
                $page->updated_at ?? $page->created_at,
                '0.8',
                'weekly'
            );
        }

        $routes = $this->getSitemapRoutes();

        foreach ($routes as $route) {
            $xml .= $this->urlTag(
                url($route['path']),
                $route['updated_at'] ?? now(),
                $route['priority'] ?? '0.6',
                $route['changefreq'] ?? 'monthly'
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    public function getRobotsTxt(): string
    {
        $robots = "User-agent: *\n";

        $seo = CmsPageSeo::where('noindex', true)->get();
        foreach ($seo as $item) {
            $page = CmsPage::find($item->page_id);
            if ($page) {
                $robots .= "Disallow: /{$page->slug}\n";
            }
        }

        $robots .= "\nSitemap: " . url('sitemap.xml') . "\n";

        return $robots;
    }

    public function validateSlug(string $slug, ?int $excludeId = null): bool
    {
        $query = CmsPage::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    protected function urlTag(string $loc, $lastmod, string $priority, string $changefreq): string
    {
        $date = $lastmod instanceof \Carbon\Carbon
            ? $lastmod->toDateString()
            : ($lastmod ? date('Y-m-d', strtotime($lastmod)) : date('Y-m-d'));

        return "  <url>\n"
            . "    <loc>" . e($loc) . "</loc>\n"
            . "    <lastmod>{$date}</lastmod>\n"
            . "    <changefreq>{$changefreq}</changefreq>\n"
            . "    <priority>{$priority}</priority>\n"
            . "  </url>\n";
    }

    protected function getOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => url('/'),
            'logo' => url('images/logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => config('app.phone', ''),
                'contactType' => 'customer service',
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'BR',
            ],
        ];
    }

    protected function getSitemapRoutes(): array
    {
        return Cache::remember('cms.sitemap.routes', 86400, function () {
            $routes = [];

            $publicRoutes = [
                ['path' => 'noticias', 'priority' => '0.7', 'changefreq' => 'daily'],
                ['path' => 'projetos', 'priority' => '0.7', 'changefreq' => 'weekly'],
                ['path' => 'transparencia', 'priority' => '0.8', 'changefreq' => 'weekly'],
                ['path' => 'contato', 'priority' => '0.5', 'changefreq' => 'monthly'],
                ['path' => 'galeria', 'priority' => '0.5', 'changefreq' => 'weekly'],
                ['path' => 'ods', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ];

            foreach ($publicRoutes as $route) {
                $route['updated_at'] = now();
                $routes[] = $route;
            }

            return $routes;
        });
    }
}
