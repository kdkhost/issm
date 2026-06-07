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
use App\Http\Requests\Admin\CmsSeoRequest;
use App\Models\CmsPage;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use App\Services\Cms\CmsSeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CmsSeoController extends Controller
{
    protected CmsSeoService $seoService;
    protected CmsCacheService $cacheService;
    protected CmsAuditService $auditService;

    public function __construct(
        CmsSeoService $seoService,
        CmsCacheService $cacheService,
        CmsAuditService $auditService
    ) {
        $this->seoService = $seoService;
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;
    }

    public function edit(int $pageId): View
    {
        $page = CmsPage::with('seo')->findOrFail($pageId);

        return view('admin.cms.seo.edit', compact('page'));
    }

    public function update(CmsSeoRequest $request, int $pageId): RedirectResponse|JsonResponse
    {
        $page = CmsPage::findOrFail($pageId);

        $seo = $this->seoService->updateSeo($pageId, $request->validated());

        $this->auditService->logUpdate('cms_seo', $seo, [], $seo->toArray());
        $this->cacheService->clearSeoCache($pageId);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'SEO atualizado com sucesso!', 'data' => $seo]);
        }

        return redirect()->route('admin.cms.seo.edit', $pageId)->with('success', 'SEO atualizado com sucesso!');
    }
}
