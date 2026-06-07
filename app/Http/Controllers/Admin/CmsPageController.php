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
use App\Http\Requests\Admin\CmsPageRequest;
use App\Models\CmsPage;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use App\Services\Cms\CmsPageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    protected CmsPageService $pageService;
    protected CmsCacheService $cacheService;
    protected CmsAuditService $auditService;

    public function __construct(
        CmsPageService $pageService,
        CmsCacheService $cacheService,
        CmsAuditService $auditService
    ) {
        $this->pageService = $pageService;
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;

        $this->middleware('can:cms.pages.view')->only(['index', 'show']);
        $this->middleware('can:cms.pages.create')->only(['create', 'store']);
        $this->middleware('can:cms.pages.edit')->only(['edit', 'update']);
        $this->middleware('can:cms.pages.delete')->only(['destroy']);
        $this->middleware('can:cms.pages.publish')->only(['publish', 'archive']);
    }

    public function index(Request $request): View
    {
        $query = CmsPage::with('sections', 'seo');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($request->get('trashed')) {
            $query->onlyTrashed();
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.cms.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.cms.pages.create');
    }

    public function store(CmsPageRequest $request): RedirectResponse|JsonResponse
    {
        $page = $this->pageService->createPage($request->validated());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Página criada com sucesso!', 'data' => $page]);
        }

        return redirect()->route('admin.cms.pages.index')->with('success', 'Página criada com sucesso!');
    }

    public function show(CmsPage $cmsPage): View
    {
        $page = $this->pageService->getPageWithRelations($cmsPage);

        return view('admin.cms.pages.show', compact('page'));
    }

    public function edit(CmsPage $cmsPage): View
    {
        return view('admin.cms.pages.edit', compact('cmsPage'));
    }

    public function update(CmsPageRequest $request, CmsPage $cmsPage): RedirectResponse|JsonResponse
    {
        $page = $this->pageService->updatePage($cmsPage, $request->validated());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Página atualizada com sucesso!', 'data' => $page]);
        }

        return redirect()->route('admin.cms.pages.index')->with('success', 'Página atualizada com sucesso!');
    }

    public function destroy(CmsPage $cmsPage): RedirectResponse|JsonResponse
    {
        $this->pageService->deletePage($cmsPage);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Página excluída com sucesso!']);
        }

        return redirect()->route('admin.cms.pages.index')->with('success', 'Página excluída com sucesso!');
    }

    public function publish(CmsPage $cmsPage): RedirectResponse|JsonResponse
    {
        $page = $this->pageService->publishPage($cmsPage);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Página publicada com sucesso!', 'data' => $page]);
        }

        return redirect()->route('admin.cms.pages.index')->with('success', 'Página publicada com sucesso!');
    }

    public function archive(CmsPage $cmsPage): RedirectResponse|JsonResponse
    {
        $page = $this->pageService->archivePage($cmsPage);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Página arquivada com sucesso!', 'data' => $page]);
        }

        return redirect()->route('admin.cms.pages.index')->with('success', 'Página arquivada com sucesso!');
    }

    public function duplicate(CmsPage $cmsPage): RedirectResponse|JsonResponse
    {
        $duplicated = $this->pageService->duplicatePage($cmsPage);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Página duplicada com sucesso!', 'data' => $duplicated]);
        }

        return redirect()->route('admin.cms.pages.edit', $duplicated)->with('success', 'Página duplicada com sucesso!');
    }

    public function toggleStatus(CmsPage $cmsPage): RedirectResponse|JsonResponse
    {
        $active = $this->pageService->toggleStatus($cmsPage);
        $message = $active ? 'Página ativada com sucesso!' : 'Página desativada com sucesso!';

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'is_active' => $active]);
        }

        return redirect()->route('admin.cms.pages.index')->with('success', $message);
    }
}
