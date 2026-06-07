<?php

namespace App\Services\Cms;

class CmsSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><ul><ol><li><a><h1><h2><h3><h4><h5><h6><blockquote><span><div><img><table><thead><tbody><tr><th><td>';

    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        $cleaned = strip_tags($html, self::ALLOWED_TAGS);

        return preg_replace('/(<[^>]+)\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '$1', $cleaned) ?? $cleaned;
    }

    public static function escape(?string $text): string
    {
        return e($text ?? '');
    }
}
