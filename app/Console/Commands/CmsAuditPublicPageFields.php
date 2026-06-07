<?php

namespace App\Console\Commands;

use App\Services\Cms\CmsFieldAuditorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CmsAuditPublicPageFields extends Command
{
    protected $signature = 'cms:audit-public-page-fields';

    protected $aliases = ['cms:audit-original-pages'];

    protected $description = 'Audita as páginas públicas originais e sugere campos administrativos para o CMS';

    public function handle(CmsFieldAuditorService $auditor): int
    {
        $this->info('Auditando campos das views públicas...');

        $report = $auditor->generateReport();
        $dir = storage_path('app/reports');

        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($dir . '/public-page-fields-audit.md', $report);

        $audit = $auditor->audit();
        $totalSuggestions = 0;
        foreach ($audit as $data) {
            $totalSuggestions += count($data['suggested_fields']);
        }

        $this->info("Views auditadas: " . count($audit));
        $this->info("Campos sugeridos: {$totalSuggestions}");
        $this->info('Relatório: storage/app/reports/public-page-fields-audit.md');

        return self::SUCCESS;
    }
}
