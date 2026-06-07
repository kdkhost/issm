<?php

namespace App\Services\Cms;

use Illuminate\Support\Facades\File;

class GraphifyyReportService
{
    public function generate(): array
    {
        $graphPath = base_path('graphify-out/graph.json');
        $graph = File::exists($graphPath)
            ? json_decode(File::get($graphPath), true)
            : ['nodes' => [], 'edges' => []];

        $nodes = $graph['nodes'] ?? [];
        $edges = $graph['edges'] ?? [];

        $publicControllers = $this->filterNodes($nodes, 'Public');
        $adminControllers = $this->filterNodes($nodes, 'Admin');
        $views = $this->filterNodes($nodes, '.blade.php');
        $models = $this->filterNodes($nodes, 'Models');

        $report = [
            'generated_at' => now()->toIso8601String(),
            'graphify_command' => 'graphify update . --no-cluster',
            'stats' => [
                'total_nodes' => count($nodes),
                'total_edges' => count($edges),
                'public_controllers' => count($publicControllers),
                'admin_controllers' => count($adminControllers),
                'views' => count($views),
                'models' => count($models),
            ],
            'public_routes' => $this->extractPublicRoutes(),
            'public_controllers' => array_map(fn ($n) => $n['source_file'] ?? $n['label'], $publicControllers),
            'admin_controllers' => array_map(fn ($n) => $n['source_file'] ?? $n['label'], $adminControllers),
            'views' => array_map(fn ($n) => $n['source_file'] ?? $n['label'], $views),
            'models' => array_map(fn ($n) => $n['source_file'] ?? $n['label'], $models),
            'dependencies' => $this->extractDependencies($edges, $nodes),
            'gaps' => $this->identifyGaps(),
        ];

        return $report;
    }

    public function saveReports(): void
    {
        $report = $this->generate();
        $dir = storage_path('app/reports');

        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(
            $dir . '/graphifyy-issm-map.json',
            json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        File::put($dir . '/graphifyy-issm-map.md', $this->toMarkdown($report));
        File::put($dir . '/public-pages-map.md', $this->publicPagesMarkdown());
    }

    private function filterNodes(array $nodes, string $pattern): array
    {
        return array_values(array_filter($nodes, function ($node) use ($pattern) {
            $file = $node['source_file'] ?? '';
            $label = $node['label'] ?? '';

            return str_contains($file, $pattern) || str_contains($label, $pattern);
        }));
    }

    private function extractPublicRoutes(): array
    {
        return array_map(fn ($p) => [
            'page_key' => $p['page_key'],
            'route_name' => $p['route_name'],
            'route_uri' => $p['route_uri'],
            'controller' => $p['controller'],
            'view' => $p['view_path'],
        ], CmsPageDefinitions::pages());
    }

    private function extractDependencies(array $edges, array $nodes): array
    {
        $nodeMap = [];
        foreach ($nodes as $node) {
            $nodeMap[$node['id'] ?? ''] = $node['source_file'] ?? $node['label'] ?? '';
        }

        $deps = [];
        foreach (array_slice($edges, 0, 100) as $edge) {
            $from = $nodeMap[$edge['source'] ?? ''] ?? ($edge['source'] ?? '');
            $to = $nodeMap[$edge['target'] ?? ''] ?? ($edge['target'] ?? '');
            if ($from && $to) {
                $deps[] = ['from' => $from, 'to' => $to, 'relation' => $edge['relation'] ?? 'unknown'];
            }
        }

        return $deps;
    }

    private function identifyGaps(): array
    {
        return [
            'home_missing_sections' => ['odsList', 'teamMembers', 'galleryItems não renderizados na view'],
            'pages_show_html_escape' => 'Conteúdo WYSIWYG escapado com e() em pages/show.blade.php',
            'show_in_menu_unused' => 'Page.show_in_menu não integrado ao menu público',
            'duplicate_queries' => 'HomeController faz múltiplas queries separadas sem eager loading',
            'settings_in_blade' => 'layouts/app.blade.php consulta Setting::get() diretamente',
        ];
    }

    private function toMarkdown(array $report): string
    {
        $lines = ["# Graphifyy — Mapa do Projeto ISSM\n"];
        $lines[] = 'Gerado em: ' . $report['generated_at'];
        $lines[] = 'Comando: `' . $report['graphify_command'] . "`\n";
        $lines[] = '## Estatísticas';
        foreach ($report['stats'] as $key => $val) {
            $lines[] = "- **{$key}**: {$val}";
        }
        $lines[] = "\n## Rotas Públicas Mapeadas";
        foreach ($report['public_routes'] as $route) {
            $lines[] = "- `{$route['route_name']}` → {$route['route_uri']} ({$route['controller']}) → {$route['view']}";
        }
        $lines[] = "\n## Controllers Públicos";
        foreach ($report['public_controllers'] as $c) {
            $lines[] = "- {$c}";
        }
        $lines[] = "\n## Gaps Identificados";
        foreach ($report['gaps'] as $key => $gap) {
            $value = is_array($gap) ? implode('; ', $gap) : $gap;
            $lines[] = "- **{$key}**: {$value}";
        }

        return implode("\n", $lines);
    }

    private function publicPagesMarkdown(): string
    {
        $lines = ["# Mapa de Páginas Públicas — ISSM CMS\n"];
        $lines[] = 'Gerado em: ' . now()->format('d/m/Y H:i:s') . "\n";

        foreach (CmsPageDefinitions::pages() as $page) {
            $lines[] = "## {$page['admin_label']} (`{$page['page_key']}`)";
            $lines[] = "- **URL**: {$page['route_uri']}";
            $lines[] = "- **Rota**: {$page['route_name']}";
            $lines[] = "- **Controller**: {$page['controller']}@{$page['method']}";
            $lines[] = "- **View**: {$page['view_path']}";
            $lines[] = "- **Editável**: " . (($page['is_editable'] ?? true) ? 'Sim' : 'Não (modelo/dinâmica)');
            if (!empty($page['sections'])) {
                $lines[] = '- **Seções**:';
                foreach ($page['sections'] as $section) {
                    $fieldCount = count($section['fields'] ?? []);
                    $lines[] = "  - {$section['section_label']} ({$section['section_key']}) — {$fieldCount} campos";
                }
            }
            $lines[] = '';
        }

        return implode("\n", $lines);
    }
}
