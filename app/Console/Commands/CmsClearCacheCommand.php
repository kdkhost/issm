<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */
class CmsClearCacheCommand extends Command
{
    protected $signature = 'cms:clear-cache';
    protected $description = 'Clear all CMS cache';

    public function handle(): int
    {
        $this->info('Clearing CMS cache...');

        try {
            app(\App\Services\Cms\CmsCacheService::class)->clearAllCmsCache();
            $this->info('CMS cache cleared successfully.');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to clear CMS cache: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
