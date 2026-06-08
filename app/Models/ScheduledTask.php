<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledTask extends Model
{
    protected $fillable = ['command', 'description', 'frequency', 'minute', 'hour', 'day_of_month', 'month', 'day_of_week', 'expression', 'active', 'last_run_at', 'next_run_at'];

    protected $casts = [
        'active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    /**
     * Preenche a expressao cron baseada nos campos individuais ou no preset de frequencia.
     */
    public function buildExpression(): string
    {
        $map = [
            'everyMinute' => '* * * * *',
            'everyFiveMinutes' => '*/5 * * * *',
            'everyFifteenMinutes' => '*/15 * * * *',
            'everyThirtyMinutes' => '*/30 * * * *',
            'hourly' => '0 * * * *',
            'daily' => '0 0 * * *',
            'weekly' => '0 0 * * 0',
            'monthly' => '0 0 1 * *',
        ];

        if (isset($map[$this->frequency])) {
            return $map[$this->frequency];
        }

        return trim("{$this->minute} {$this->hour} {$this->day_of_month} {$this->month} {$this->day_of_week}");
    }

    /**
     * Calcula a proxima data de execucao baseada na expressao cron.
     */
    public function nextRunAt(): ?\DateTime
    {
        try {
            $cron = new \Cron\CronExpression($this->buildExpression());
            return $cron->getNextRunDate();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function defaultTasks(): array
    {
        return [
            [
                'command' => 'transparency:sync-drive',
                'description' => 'Sincroniza documentos do Google Drive com o Portal da Transparencia',
                'frequency' => 'daily',
                'minute' => '0',
                'hour' => '0',
                'day_of_month' => '*',
                'month' => '*',
                'day_of_week' => '*',
                'active' => false,
            ],
        ];
    }
}
