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
use App\Models\CmsPage;
use App\Models\CmsVersion;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use App\Services\Cms\CmsVersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsVersionController extends Controller
{
    protected CmsVersionService $versionService;
    protected CmsCacheService $cacheService;
    protected CmsAuditService $auditService;

    public function __construct(
        CmsVersionService $versionService,
        CmsCacheService $cacheService,
        CmsAuditService $auditService
    ) {
        $this->versionService = $versionService;
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;
    }

    public function index(Request $request): View
    {
        $query = CmsVersion::with('user')->latest();

        if ($modelType = $request->get('model_type')) {
            $query->where('versionable_type', $modelType);
        }

        if ($modelId = $request->get('model_id')) {
            $query->where('versionable_id', $modelId);
        }

        $versions = $query->paginate(30);

        return view('admin.cms.versions.index', compact('versions'));
    }

    public function show(CmsVersion $cmsVersion): View
    {
        $version = $cmsVersion->load('user');

        return view('admin.cms.versions.show', compact('version'));
    }

    public function restore(CmsVersion $cmsVersion): RedirectResponse|JsonResponse
    {
        try {
            $model = $this->versionService->restoreVersion($cmsVersion);

            $this->auditService->logRestore('cms_version', $cmsVersion);

            if ($model instanceof CmsPage) {
                $this->cacheService->clearPageCache($model->slug);
            }

            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Versão restaurada com sucesso!', 'data' => $model]);
            }

            return redirect()->back()->with('success', 'Versão restaurada com sucesso!');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Erro ao restaurar versão: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Erro ao restaurar versão: ' . $e->getMessage());
        }
    }

    public function diff(Request $request): JsonResponse
    {
        $request->validate([
            'version_id_1' => 'required|integer|exists:cms_versions,id',
            'version_id_2' => 'required|integer|exists:cms_versions,id',
        ]);

        $diff = $this->versionService->compareVersions(
            $request->integer('version_id_1'),
            $request->integer('version_id_2')
        );

        return response()->json(['success' => true, 'data' => $diff]);
    }
}
