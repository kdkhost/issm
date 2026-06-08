<?php

use App\Services\Cms\CmsContentService;

if (!function_exists('cms')) {
    function cms(string $pageKey, string $sectionKey, string $fieldKey, mixed $default = ''): string
    {
        return app(CmsContentService::class)->get($pageKey, $sectionKey, $fieldKey, $default);
    }
}

if (!function_exists('cms_html')) {
    function cms_html(string $pageKey, string $sectionKey, string $fieldKey, mixed $default = ''): string
    {
        return app(CmsContentService::class)->getHtml($pageKey, $sectionKey, $fieldKey, $default);
    }
}

if (!function_exists('cms_page')) {
    function cms_page(string $pageKey): ?\App\Models\CmsPublicPage
    {
        return \App\Models\CmsPublicPage::where('page_key', $pageKey)->where('is_active', true)->first();
    }
}
