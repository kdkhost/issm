<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

(new App\Services\Cms\GraphifyyReportService)->saveReports();
(new App\Services\Cms\CmsReportGenerator)->saveAll();

$audit = (new App\Services\Cms\CmsFieldAuditorService)->generateReport();
$dir = storage_path('app/reports');
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}
file_put_contents($dir . '/public-page-fields-audit.md', $audit);

echo "Relatórios CMS gerados em storage/app/reports/\n";
