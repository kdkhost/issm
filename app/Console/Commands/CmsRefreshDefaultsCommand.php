<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CmsRefreshDefaultsCommand extends Command
{
    protected $signature = 'cms:refresh-defaults {--force : Force re-create all sections and blocks}';
    protected $description = 'Re-seed default CMS pages with real frontend content';

    public function handle(): int
    {
        $this->info('Refreshing default CMS pages with real frontend content...');

        $force = $this->option('force');

        if ($force) {
            $this->warn('Deleting all existing sections and blocks...');
            DB::table('cms_blocks')->delete();
            DB::table('cms_sections')->delete();
            $this->info('Done. Seeder will create fresh sections and blocks.');
        }

        Artisan::call('db:seed', [
            '--class' => 'Database\Seeders\CmsDefaultSeeder',
            '--force' => true,
        ]);

        $this->line(Artisan::output());

        Artisan::call('cms:clear-cache');

        $this->info('CMS defaults refreshed successfully.');
        $this->warn('Use --force to re-create all sections and blocks from scratch.');

        return self::SUCCESS;
    }
}
