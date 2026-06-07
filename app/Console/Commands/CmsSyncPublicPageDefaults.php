<?php

namespace App\Console\Commands;

use App\Services\Cms\CmsSyncDefaultsService;
use Illuminate\Console\Command;

class CmsSyncPublicPageDefaults extends Command
{
    protected $signature = 'cms:sync-public-page-defaults';

    protected $aliases = ['cms:sync-original-page-fields'];

    protected $description = 'Sincroniza valores padrão dos campos das páginas públicas originais sem sobrescrever edições existentes';

    public function handle(CmsSyncDefaultsService $syncer): int
    {
        $this->info('Sincronizando valores padrão dos campos CMS...');

        $stats = $syncer->syncAll();

        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total campos', $stats['total']],
                ['Sincronizados', $stats['synced']],
                ['Ignorados (já editados)', $stats['skipped']],
            ]
        );

        return self::SUCCESS;
    }
}
