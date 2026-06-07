<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */
class CmsHealthCheckCommand extends Command
{
    protected $signature = 'cms:health-check';
    protected $description = 'Run CMS health checks';

    private array $cmsTables = [
        'cms_pages',
        'cms_sections',
        'cms_blocks',
        'cms_menus',
        'cms_media',
        'cms_settings',
        'cms_audit_logs',
        'cms_versions',
        'cms_seo',
    ];

    private array $requiredDirs = [
        'storage' => 'storage',
        'uploads' => 'public/uploads',
        'logs' => 'storage/logs',
        'cache' => 'bootstrap/cache',
        'views' => 'resources/views',
        'lang' => 'lang',
    ];

    private array $cmsEnvVars = [
        'CMS_CACHE_ENABLED',
        'CMS_CACHE_TTL',
        'CMS_UPLOAD_MAX_MB',
        'CMS_VERSIONING_ENABLED',
        'CMS_AUDIT_ENABLED',
        'CMS_RATE_LIMIT_LOGIN',
        'CMS_RATE_LIMIT_CONTACT',
        'CMS_RATE_LIMIT_UPLOAD',
        'CMS_RATE_LIMIT_API',
        'CMS_SECURITY_HEADERS',
    ];

    public function handle(): int
    {
        $this->info('Running CMS health checks...');
        $this->newLine();

        $results = array_merge(
            $this->checkTables(),
            $this->checkDirectories(),
            $this->checkPermissions(),
            $this->checkConfiguration(),
            $this->checkEnvVariables(),
            $this->checkUtf8WithoutBom(),
        );

        $this->newLine();
        $this->table(
            ['Check', 'Status', 'Details'],
            $results
        );

        $failed = collect($results)->where('status', 'FAIL')->count();
        $warnings = collect($results)->where('status', 'WARN')->count();
        $passed = collect($results)->where('status', 'PASS')->count();

        $this->newLine();
        $this->info("Results: {$passed} passed, {$warnings} warnings, {$failed} failed.");

        if ($failed > 0) {
            $this->warn('Some checks failed. Please review the details above.');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function checkTables(): array
    {
        $results = [];
        $existingTables = [];

        try {
            $existingTables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        } catch (\Throwable $e) {
            return [['Tables', 'FAIL', 'Could not connect to database: ' . $e->getMessage()]];
        }

        foreach ($this->cmsTables as $table) {
            $exists = in_array($table, $existingTables);
            $results[] = [
                'Tables',
                $exists ? 'PASS' : 'FAIL',
                $exists ? "Table '{$table}' exists" : "Missing table: {$table}",
            ];
        }

        return $results;
    }

    private function checkDirectories(): array
    {
        $results = [];
        $basePath = base_path();

        foreach ($this->requiredDirs as $key => $dir) {
            $fullPath = $basePath . DIRECTORY_SEPARATOR . $dir;
            $exists = File::isDirectory($fullPath);
            $writable = $exists && is_writable($fullPath);

            $status = 'PASS';
            $details = "Directory '{$dir}' exists" . ($writable ? ' and is writable' : '');

            if (!$exists) {
                $status = 'FAIL';
                $details = "Missing directory: {$dir}";
            } elseif (!$writable) {
                $status = 'WARN';
                $details = "Directory '{$dir}' exists but is not writable";
            }

            $results[] = ['Directories', $status, $details];
        }

        return $results;
    }

    private function checkPermissions(): array
    {
        $results = [];
        $permissions = Config::get('cms.permissions', []);

        if (empty($permissions)) {
            return [['Permissions', 'FAIL', 'cms.permissions config is empty or missing']];
        }

        $expected = ['pages', 'sections', 'blocks', 'media', 'seo', 'audit', 'settings', 'cache', 'versions', 'menus'];

        foreach ($expected as $key) {
            $hasKey = array_key_exists($key, $permissions);
            $hasActions = $hasKey && is_array($permissions[$key]) && count($permissions[$key]) > 0;

            $status = 'PASS';
            $details = "Permission '{$key}' configured with " . count($permissions[$key] ?? []) . ' actions';

            if (!$hasKey) {
                $status = 'FAIL';
                $details = "Missing permission: {$key}";
            } elseif (!$hasActions) {
                $status = 'FAIL';
                $details = "Permission '{$key}' has no actions defined";
            }

            $results[] = ['Permissions', $status, $details];
        }

        return $results;
    }

    private function checkConfiguration(): array
    {
        $results = [];
        $configKeys = [
            'cms.cache' => 'array',
            'cms.uploads' => 'array',
            'cms.sanitize' => 'array',
            'cms.versioning' => 'array',
            'cms.audit' => 'array',
            'cms.seo' => 'array',
            'cms.rate_limit' => 'array',
            'cms.security' => 'array',
            'cms.permissions' => 'array',
        ];

        foreach ($configKeys as $key => $expectedType) {
            $value = Config::get($key);
            $actualType = gettype($value);

            $status = $value !== null ? 'PASS' : 'FAIL';
            $details = $value !== null
                ? "Config '{$key}' is set ({$actualType})"
                : "Missing config: {$key}";

            $results[] = ['Configuration', $status, $details];
        }

        return $results;
    }

    private function checkEnvVariables(): array
    {
        $results = [];

        foreach ($this->cmsEnvVars as $var) {
            $value = env($var);
            $isSet = $value !== null;

            $status = $isSet ? 'PASS' : 'WARN';
            $details = $isSet
                ? "{$var} is set to '" . $this->truncateEnvValue($value) . "'"
                : "{$var} is not defined in .env (using default)";

            $results[] = ['.env Variables', $status, $details];
        }

        return $results;
    }

    private function checkUtf8WithoutBom(): array
    {
        $results = [];
        $criticalFiles = [
            'config/app.php',
            'config/cms.php',
            '.env',
        ];

        foreach ($criticalFiles as $file) {
            $fullPath = base_path($file);

            if (!File::exists($fullPath)) {
                $results[] = ['UTF-8 BOM', 'WARN', "File not found: {$file}"];
                continue;
            }

            $content = File::get($fullPath);
            $hasBom = substr($content, 0, 3) === "\xEF\xBB\xBF";

            $status = $hasBom ? 'FAIL' : 'PASS';
            $details = $hasBom
                ? "File '{$file}' has UTF-8 BOM"
                : "File '{$file}' is UTF-8 without BOM";

            $results[] = ['UTF-8 BOM', $status, $details];
        }

        return $results;
    }

    private function truncateEnvValue($value): string
    {
        $str = (string) $value;
        return strlen($str) > 50 ? substr($str, 0, 47) . '...' : $str;
    }
}
