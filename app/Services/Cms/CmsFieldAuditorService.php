<?php

namespace App\Services\Cms;

use Illuminate\Support\Facades\File;

class CmsFieldAuditorService
{
    private array $publicViews = [
        'home.blade.php',
        'about/index.blade.php',
        'ods/index.blade.php',
        'gallery/index.blade.php',
        'news/index.blade.php',
        'news/show.blade.php',
        'projects/index.blade.php',
        'projects/show.blade.php',
        'contact/index.blade.php',
        'transparency/index.blade.php',
        'pages/show.blade.php',
    ];

    public function audit(): array
    {
        $results = [];

        foreach ($this->publicViews as $view) {
            $path = resource_path('views/' . $view);
            if (!File::exists($path)) {
                continue;
            }

            $content = File::get($path);
            $results[$view] = [
                'hardcoded_texts' => $this->extractHardcodedTexts($content),
                'images' => $this->extractImages($content),
                'links' => $this->extractLinks($content),
                'variables' => $this->extractVariables($content),
                'suggested_fields' => $this->suggestFields($content, $view),
            ];
        }

        return $results;
    }

    public function generateReport(): string
    {
        $audit = $this->audit();
        $lines = ["# Auditoria de Campos CMS — Páginas Públicas ISSM\n"];
        $lines[] = 'Gerado em: ' . now()->format('d/m/Y H:i:s') . "\n";

        foreach ($audit as $view => $data) {
            $lines[] = "## {$view}\n";
            $lines[] = '### Variáveis Blade';
            foreach ($data['variables'] as $var) {
                $lines[] = "- `{$var}`";
            }
            $lines[] = "\n### Textos hardcoded detectados";
            foreach (array_slice($data['hardcoded_texts'], 0, 20) as $text) {
                $lines[] = '- "' . addslashes(substr($text, 0, 80)) . '"';
            }
            $lines[] = "\n### Campos sugeridos";
            foreach ($data['suggested_fields'] as $field) {
                $lines[] = "- `{$field['section']}.{$field['key']}` ({$field['type']}): {$field['label']}";
            }
            $lines[] = "\n### Imagens";
            foreach ($data['images'] as $img) {
                $lines[] = "- {$img}";
            }
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    private function extractHardcodedTexts(string $content): array
    {
        preg_match_all('/>([^<>{}\$]+)</', $content, $matches);
        $texts = array_filter(array_map('trim', $matches[1] ?? []), fn ($t) => strlen($t) > 3 && !preg_match('/^\s*$/', $t));

        return array_values(array_unique(array_slice($texts, 0, 50)));
    }

    private function extractImages(string $content): array
    {
        preg_match_all('/asset\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    private function extractLinks(string $content): array
    {
        preg_match_all('/route\([\'"]([^\'"]+)[\'"]/', $content, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    private function extractVariables(string $content): array
    {
        preg_match_all('/\{\{\s*\$(\w+)/', $content, $matches);
        preg_match_all('/\{\!\!\s*\$(\w+)/', $content, $matches2);
        $vars = array_merge($matches[1] ?? [], $matches2[1] ?? []);

        return array_values(array_unique($vars));
    }

    private function suggestFields(string $content, string $view): array
    {
        $pageKey = str_replace(['.blade.php', '/index', '/show'], '', $view);
        $pageKey = str_replace('/', '_', $pageKey);
        $suggestions = [];

        if (preg_match_all('/<h1[^>]*>([^<]+)/', $content, $h1)) {
            foreach ($h1[1] as $i => $text) {
                $suggestions[] = [
                    'section' => 'hero',
                    'key' => 'title' . ($i > 0 ? '_' . $i : ''),
                    'type' => 'text',
                    'label' => 'Título Hero',
                    'default' => trim(strip_tags($text)),
                ];
            }
        }

        if (preg_match_all('/<h2[^>]*>([^<]+)/', $content, $h2)) {
            foreach ($h2[1] as $i => $text) {
                $suggestions[] = [
                    'section' => 'content',
                    'key' => 'section_title_' . $i,
                    'type' => 'text',
                    'label' => 'Título de Seção',
                    'default' => trim(strip_tags($text)),
                ];
            }
        }

        return $suggestions;
    }
}
