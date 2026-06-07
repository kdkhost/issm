<?php

namespace App\Services\Security;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */
class SanitizeHtmlService
{
    protected array $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li',
        'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'blockquote', 'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'img', 'figure', 'figcaption', 'span', 'div',
        'hr', 'pre', 'code', 'sub', 'sup', 'small',
        'mark', 'del', 'ins', 'abbr',
    ];

    protected array $allowedAttributes = [
        'a' => ['href', 'target', 'rel', 'title'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'class'],
        'abbr' => ['title'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan'],
        '* ,' => ['class', 'id'],
    ];

    protected array $dangerousPatterns = [
        '/onerror\s*=/i',
        '/onclick\s*=/i',
        '/onload\s*=/i',
        '/onmouseover\s*=/i',
        '/onmouseout\s*=/i',
        '/onsubmit\s*=/i',
        '/onchange\s*=/i',
        '/onfocus\s*=/i',
        '/onblur\s*=/i',
        '/onkeydown\s*=/i',
        '/onkeyup\s*=/i',
        '/onkeypress\s*=/i',
        '/ondblclick\s*=/i',
        '/onmousedown\s*=/i',
        '/onmouseup\s*=/i',
        '/onmousemove\s*=/i',
        '/onmouseenter\s*=/i',
        '/onmouseleave\s*=/i',
        '/onscroll\s*=/i',
        '/onwheel\s*=/i',
        '/ondrag\s*=/i',
        '/ondrop\s*=/i',
        '/style\s*=/i',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/data\s*:\s*text\/html/i',
        '/expression\s*\(/i',
    ];

    public function __construct()
    {
        $configTags = config('cms.sanitize.allowed_tags');
        if (is_array($configTags) && count($configTags) > 0) {
            $this->allowedTags = $configTags;
        }
    }

    public function sanitize(string $html): string
    {
        $html = $this->cleanScriptTags($html);

        $allowedTags = '<' . implode('><', $this->allowedTags) . '>';
        $html = strip_tags($html, $allowedTags);

        if (empty(trim($html))) {
            return '';
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $this->stripDangerousAttributes($dom);

        $body = $dom->getElementsByTagName('body')->item(0);
        if ($body) {
            $innerHtml = '';
            foreach ($body->childNodes as $child) {
                $innerHtml .= $dom->saveHTML($child);
            }
            return $innerHtml;
        }

        $result = $dom->saveHTML();
        $result = preg_replace('/^<\?xml encoding="UTF-8">/', '', $result);

        return $result;
    }

    public function cleanScriptTags(string $html): string
    {
        $patterns = [
            '/<script\b[^>]*>.*?<\/script>/is' => '',
            '/<iframe\b[^>]*>.*?<\/iframe>/is' => '',
            '/<object\b[^>]*>.*?<\/object>/is' => '',
            '/<embed\b[^>]*>.*?<\/embed>/is' => '',
            '/<style\b[^>]*>.*?<\/style>/is' => '',
            '/<noscript\b[^>]*>.*?<\/noscript>/is' => '',
            '/<applet\b[^>]*>.*?<\/applet>/is' => '',
            '/<meta\b[^>]*>/i' => '',
            '/<link\b[^>]*>/i' => '',
            '/<base\b[^>]*>/i' => '',
            '/<frame\b[^>]*>/i' => '',
            '/<frameset\b[^>]*>.*?<\/frameset>/is' => '',
            '/<' . '!--.*?-->/s' => '',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $html);
    }

    public function sanitizeUrl(string $url): string
    {
        $url = trim(strip_tags($url));
        $url = filter_var($url, FILTER_SANITIZE_URL);

        $allowedSchemes = ['http', 'https', 'mailto', 'tel'];
        $parsed = parse_url($url);

        if ($parsed === false || !isset($parsed['scheme'])) {
            if (strpos($url, '/') === 0 || strpos($url, '#') === 0) {
                return $url;
            }
            return '';
        }

        $scheme = strtolower($parsed['scheme']);

        if (in_array($scheme, $allowedSchemes, true)) {
            return $url;
        }

        if ($scheme === '#' && isset($parsed['fragment'])) {
            return '#' . $parsed['fragment'];
        }

        return '';
    }

    protected function stripDangerousAttributes(\DOMDocument $dom): void
    {
        $xpath = new \DOMXPath($dom);
        $allElements = $xpath->query('//*');

        foreach ($allElements as $element) {
            $tagName = strtolower($element->tagName);
            $allowed = $this->getAllowedAttributesForTag($tagName);

            $attributesToRemove = [];
            foreach ($element->attributes as $attr) {
                $attrName = strtolower($attr->name);

                foreach ($this->dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $attrName) || preg_match($pattern, $attr->value)) {
                        $attributesToRemove[] = $attr->name;
                        continue 2;
                    }
                }

                if (!in_array($attrName, $allowed, true)) {
                    $attributesToRemove[] = $attr->name;
                }
            }

            foreach ($attributesToRemove as $attrName) {
                $element->removeAttribute($attrName);
            }
        }
    }

    protected function getAllowedAttributesForTag(string $tagName): array
    {
        $allowed = $this->allowedAttributes['* ,'] ?? ['class', 'id'];

        if (isset($this->allowedAttributes[$tagName])) {
            $allowed = array_merge($allowed, $this->allowedAttributes[$tagName]);
        }

        return array_unique($allowed);
    }
}
