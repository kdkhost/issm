<?php

namespace App\Http\Controllers\Public;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use App\Http\Controllers\Controller;
use App\Models\CmsRedirect;
use App\Services\Cms\CmsCacheService;
use App\Services\Cms\CmsRenderService;
use App\Services\Cms\CmsSeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CmsPublicController extends Controller
{
    protected CmsRenderService $renderService;
    protected CmsSeoService $seoService;
    protected CmsCacheService $cacheService;

    public function __construct(
        CmsRenderService $renderService,
        CmsSeoService $seoService,
        CmsCacheService $cacheService
    ) {
        $this->renderService = $renderService;
        $this->seoService = $seoService;
        $this->cacheService = $cacheService;
    }

    public function show(string $slug): View|string
    {
        return $this->renderService->renderPage($slug);
    }

    public function sitemap(): Response
    {
        $xml = $this->seoService->generateSitemap();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robotsTxt(): Response
    {
        $content = $this->seoService->getRobotsTxt();

        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function redirect(string $from): RedirectResponse
    {
        $redirect = CmsRedirect::where('from_url', $from)
            ->where('is_active', true)
            ->first();

        if (!$redirect) {
            abort(404, "Página '{$from}' não encontrada.");
        }

        $redirect->increment('hit_count');
        $redirect->update(['last_hit_at' => now()]);

        return redirect($redirect->to_url, $redirect->status_code);
    }
}
