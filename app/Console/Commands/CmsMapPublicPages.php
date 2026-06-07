<?php

namespace App\Console\Commands;

use App\Services\Cms\CmsPageMapperService;
use App\Services\Cms\CmsReportGenerator;
use App\Services\Cms\GraphifyyReportService;
use Illuminate\Console\Command;

class CmsMapPublicPages extends Command
{
    protected $signature = 'cms:map-public-pages';

    protected $description = 'Mapeia rotas públicas reais e atualiza cms_public_pages';

    public function handle(CmsPageMapperService $mapper, GraphifyyReportService $reporter, CmsReportGenerator $cmsReports): int
    {
        $this->info('Mapeando páginas públicas reais do ISSM...');

        $stats = $mapper->mapAll();
        $reporter->saveReports();
        $cmsReports->saveAll();

        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total mapeadas', $stats['total']],
                ['Criadas', $stats['created']],
                ['Atualizadas', $stats['updated']],
                ['Pendentes revisão', $stats['review']],
            ]
        );

        $this->info('Relatórios salvos em storage/app/reports/');

        return self::SUCCESS;
    }
}
