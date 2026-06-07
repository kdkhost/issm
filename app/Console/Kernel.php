<?php

namespace App\Console;

use App\Console\Commands\CmsAuditHardcodedContentCommand;
use App\Console\Commands\CmsAuditPublicPageFields;
use App\Console\Commands\CmsClearCacheCommand;
use App\Console\Commands\CmsHealthCheckCommand;
use App\Console\Commands\CmsMapPublicPages;
use App\Console\Commands\CmsRefreshDefaultsCommand;
use App\Console\Commands\CmsSyncPublicPageDefaults;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Comandos CMS registrados explicitamente para garantir descoberta em produção.
     */
    protected $commands = [
        CmsMapPublicPages::class,
        CmsSyncPublicPageDefaults::class,
        CmsAuditPublicPageFields::class,
        CmsAuditHardcodedContentCommand::class,
        CmsClearCacheCommand::class,
        CmsHealthCheckCommand::class,
        CmsRefreshDefaultsCommand::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
