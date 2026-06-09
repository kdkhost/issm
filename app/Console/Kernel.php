<?php

namespace App\Console;

use App\Console\Commands\CmsAuditPublicPageFields;
use App\Console\Commands\CmsMapPublicPages;
use App\Console\Commands\CmsSyncPublicPageDefaults;
use App\Console\Commands\SyncTransparencyFromDrive;
use App\Models\ScheduledTask;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Comandos registrados explicitamente para garantir descoberta em produção.
     */
    protected $commands = [
        CmsMapPublicPages::class,
        CmsSyncPublicPageDefaults::class,
        CmsAuditPublicPageFields::class,
        SyncTransparencyFromDrive::class,
    ];
    /**
     * Define the application's command schedule.
     * Le as tasks ativas do banco de dados (scheduled_tasks) e agenda automaticamente.
     */
    protected function schedule(Schedule $schedule): void
    {
        try {
            $tasks = ScheduledTask::where('active', true)->get();
            foreach ($tasks as $task) {
                $expression = $task->buildExpression();
                $event = $schedule->command($task->command)->cron($expression);

                $event->onSuccess(function () use ($task) {
                    $task->update([
                        'last_run_at' => now(),
                        'next_run_at' => $task->nextRunAt(),
                    ]);
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Scheduler: falha ao carregar tarefas agendadas do banco: ' . $e->getMessage());
        }
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
