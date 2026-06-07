<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */
class CmsAuditHardcodedContentCommand extends Command
{
    protected $signature = 'cms:audit-hardcoded-content';
    protected $description = 'Audit blade views for hardcoded content and generate report';

    private array $patterns = [
        'text' => [
            '/[>]([A-Z][a-zA-Z\s,.!?]{3,60})[<]/',
            '/[\'"]([A-Z][a-zA-Z\s]{3,60})[\'"]/',
        ],
        'image_src' => [
            '/<img[^>]+src=["\']([^"\']+)["\']/i',
        ],
        'link_href' => [
            '/<a[^>]+href=["\']([^"\']+)["\']/i',
        ],
        'localized_url' => [
            '/["\'](\/[a-z]{2}\/[a-zA-Z0-9_\/-]+)["\']/',
        ],
    ];

    private array $excludeDirs = ['vendor', 'node_modules', 'storage', 'cache'];

    public function handle(): int
    {
        $this->info('Scanning views for hardcoded content...');

        $viewsPath = resource_path('views');
        if (!File::isDirectory($viewsPath)) {
            $this->error('Views directory not found: ' . $viewsPath);
            return Command::FAILURE;
        }

        $files = File::allFiles($viewsPath);
        $results = [];

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $content = $file->getContents();

            $fileResults = $this->scanFile($relativePath, $content);
            if (!empty($fileResults)) {
                $results[$relativePath] = $fileResults;
            }
        }

        $reportDir = storage_path('app/reports');
        File::ensureDirectoryExists($reportDir);

        $jsonPath = $reportDir . '/hardcoded-content-report.json';
        File::put($jsonPath, json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $mdPath = $reportDir . '/hardcoded-content-report.md';
        $mdContent = $this->generateMarkdownReport($results);
        File::put($mdPath, $mdContent);

        $totalIssues = collect($results)->flatten(1)->count();
        $totalFiles = count($results);

        $this->newLine();
        $this->table(
            ['Metric', 'Value'],
            [
                ['Files scanned', count($files)],
                ['Files with issues', $totalFiles],
                ['Total hardcoded items', $totalIssues],
                ['Report JSON', $jsonPath],
                ['Report MD', $mdPath],
            ]
        );

        $this->info("Audit complete. Found {$totalIssues} potential hardcoded items in {$totalFiles} files.");

        return Command::SUCCESS;
    }

    private function scanFile(string $relativePath, string $content): array
    {
        $results = [];

        foreach ($this->patterns as $type => $typePatterns) {
            foreach ($typePatterns as $pattern) {
                if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[1] ?? $matches[0] as $match) {
                        $text = $match[0];
                        $lineNumber = substr_count(substr($content, 0, $match[1]), "\n") + 1;

                        $results[] = [
                            'type' => $type,
                            'value' => $this->truncate($text, 100),
                            'line' => $lineNumber,
                        ];
                    }
                }
            }
        }

        return $results;
    }

    private function truncate(string $value, int $length = 100): string
    {
        return Str::limit($value, $length);
    }

    private function generateMarkdownReport(array $results): string
    {
        $md = "# Hardcoded Content Audit Report\n\n";
        $md .= 'Generated: ' . now()->format('Y-m-d H:i:s') . "\n\n";
        $md .= "## Summary\n\n";
        $md .= '| Metric | Value |' . "\n";
        $md .= '|--------|-------|' . "\n";
        $md .= '| Total Files with Issues | ' . count($results) . " |\n";
        $md .= '| Total Hardcoded Items | ' . collect($results)->flatten(1)->count() . " |\n\n";
        $md .= "## Details\n\n";

        foreach ($results as $file => $items) {
            $md .= "### `{$file}`\n\n";
            $md .= "| # | Type | Value | Line |\n";
            $md .= "|---|------|-------|------|\n";

            foreach ($items as $index => $item) {
                $md .= '| ' . ($index + 1) . ' | ' . $item['type'] . ' | `' . e($item['value']) . '` | ' . $item['line'] . " |\n";
            }

            $md .= "\n";
        }

        return $md;
    }
}
