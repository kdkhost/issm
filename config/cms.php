<?php

return [
    'cache' => [
        'enabled' => env('CMS_CACHE_ENABLED', true),
        'ttl' => env('CMS_CACHE_TTL', 3600),
        'public_page_cache' => env('CMS_PUBLIC_PAGE_CACHE', true),
    ],
    'uploads' => [
        'max_size' => env('CMS_UPLOAD_MAX_MB', 10) * 1024 * 1024,
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
        'blocked_extensions' => ['php', 'phtml', 'phar', 'js', 'html', 'htm', 'exe', 'sh', 'bat', 'cmd', 'asp', 'aspx', 'jsp'],
        'path' => 'uploads',
    ],
    'sanitize' => [
        'allowed_tags' => ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'img', 'figure', 'figcaption', 'span', 'div', 'hr', 'pre', 'code', 'sub', 'sup', 'small', 'mark', 'del', 'ins', 'abbr'],
        'allowed_attributes' => ['href', 'target', 'rel', 'title', 'src', 'alt', 'width', 'height', 'class', 'id', 'name', 'data-*'],
        'strip_scripts' => true,
    ],
    'versioning' => [
        'enabled' => env('CMS_VERSIONING_ENABLED', true),
        'max_versions_per_entity' => 50,
    ],
    'audit' => [
        'enabled' => env('CMS_AUDIT_ENABLED', true),
        'log_login' => true,
        'log_failed_login' => true,
        'log_logout' => true,
        'log_crud' => true,
    ],
    'seo' => [
        'default_title' => env('APP_NAME', 'ISSM'),
        'default_description' => '',
        'sitemap_enabled' => true,
        'sitemap_cache_ttl' => 3600,
    ],
    'rate_limit' => [
        'login' => env('CMS_RATE_LIMIT_LOGIN', 5),
        'contact' => env('CMS_RATE_LIMIT_CONTACT', 3),
        'upload' => env('CMS_RATE_LIMIT_UPLOAD', 10),
        'api' => env('CMS_RATE_LIMIT_API', 60),
    ],
    'security' => [
        'headers' => env('CMS_SECURITY_HEADERS', true),
        'honeypot_enabled' => true,
        'block_suspicious' => true,
        'csp_enabled' => false,
    ],
    'permissions' => [
        'pages' => ['view', 'create', 'edit', 'delete', 'publish'],
        'sections' => ['view', 'create', 'edit', 'delete'],
        'blocks' => ['view', 'create', 'edit', 'delete'],
        'media' => ['view', 'upload', 'edit', 'delete'],
        'seo' => ['view', 'edit'],
        'audit' => ['view'],
        'settings' => ['view', 'edit'],
        'cache' => ['clear'],
        'versions' => ['view', 'restore'],
        'menus' => ['view', 'create', 'edit', 'delete'],
    ],
];
