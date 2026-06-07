<?php

namespace App\Providers;

use App\Models\CmsMenu;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Cms\CmsCacheService::class);
        $this->app->singleton(\App\Services\Cms\CmsAuditService::class);
        $this->app->singleton(\App\Services\Cms\CmsPageService::class);
        $this->app->singleton(\App\Services\Cms\CmsRenderService::class);
        $this->app->singleton(\App\Services\Cms\CmsSeoService::class);
        $this->app->singleton(\App\Services\Cms\CmsMenuService::class);
        $this->app->singleton(\App\Services\Cms\CmsVersionService::class);
        $this->app->singleton(\App\Services\Security\SanitizeHtmlService::class);
        $this->app->singleton(\App\Services\Security\UploadSecurityService::class);
        $this->app->singleton(\App\Services\Security\RequestSecurityService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('pt_BR');
        setlocale(LC_TIME, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');
        $this->configureMailFromDatabase();
        $this->shareCmsMenus();
    }

    private function shareCmsMenus(): void
    {
        try {
            if (!Schema::hasTable('cms_menus')) {
                return;
            }

            $locations = ['header', 'sidebar', 'bottom', 'footer'];
            $cmsMenus = [];

            foreach ($locations as $location) {
                $cmsMenus[$location] = CmsMenu::where('location', $location)
                    ->where('is_active', true)
                    ->with(['items' => function ($q) {
                        $q->where('is_active', true)->orderBy('sort_order');
                    }])
                    ->first();
            }

            View::share('cmsMenus', $cmsMenus);
        } catch (\Throwable $e) {
            // Table might not exist yet during migrations
        }
    }

    /**
     * Override mail configuration with database settings (if available).
     */
    private function configureMailFromDatabase(): void
    {
        try {
            if (!Schema::hasTable('settings')) {
                return;
            }

            $host = Setting::get('mail_host');
            if (empty($host)) {
                return;
            }

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', 465));
            Config::set('mail.mailers.smtp.username', Setting::get('mail_username', ''));
            Config::set('mail.mailers.smtp.password', Setting::get('mail_password', ''));
            Config::set('mail.mailers.smtp.encryption', Setting::get('mail_encryption', 'ssl'));

            $fromAddress = Setting::get('mail_from_address');
            $fromName = Setting::get('mail_from_name', config('app.name'));
            if ($fromAddress) {
                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', $fromName);
            }
        } catch (\Throwable $e) {
            // Silently fail — DB might not be available yet
        }
    }
}
