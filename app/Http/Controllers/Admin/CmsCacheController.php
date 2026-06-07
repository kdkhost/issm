<?php

namespace App\Http\Controllers\Admin;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use App\Http\Controllers\Controller;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CmsCacheController extends Controller
{
    protected CmsCacheService $cacheService;
    protected CmsAuditService $auditService;

    public function __construct(CmsCacheService $cacheService, CmsAuditService $auditService)
    {
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;
    }

    public function index(): View
    {
        $cacheStatus = [
            'enabled' => config('cms.cache.enabled', true),
            'ttl' => config('cms.cache.ttl', 3600),
            'keys' => Cache::get('cms.cache_keys', []),
        ];

        return view('admin.cms.cache.index', compact('cacheStatus'));
    }

    public function clearCache(Request $request): RedirectResponse|JsonResponse
    {
        $key = $request->get('key');

        if ($key) {
            $this->cacheService->forget($key);
        } else {
            $this->cacheService->clearAllCmsCache();
        }

        $this->auditService->logCacheClear($key ? "key:{$key}" : null);

        $message = $key ? "Cache '{$key}' limpo com sucesso!" : 'Cache CMS limpo com sucesso!';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('admin.cms.cache.index')->with('success', $message);
    }

    public function clearPageCache(Request $request): RedirectResponse|JsonResponse
    {
        $slug = $request->get('slug');

        if ($slug) {
            $this->cacheService->clearPageCache($slug);
            $message = "Cache da página '{$slug}' limpo com sucesso!";
        } else {
            $this->cacheService->clearPagesCache();
            $message = 'Cache de páginas limpo com sucesso!';
        }

        $this->auditService->logCacheClear('cms_page');

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('admin.cms.cache.index')->with('success', $message);
    }
}
