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
use App\Http\Requests\Admin\CmsSectionRequest;
use App\Models\CmsSection;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsSectionController extends Controller
{
    protected CmsCacheService $cacheService;
    protected CmsAuditService $auditService;

    public function __construct(CmsCacheService $cacheService, CmsAuditService $auditService)
    {
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;
    }

    public function index(Request $request): View
    {
        $query = CmsSection::with('page', 'blocks');

        if ($pageId = $request->get('page_id')) {
            $query->where('cms_page_id', $pageId);
        }

        $sections = $query->orderBy('sort_order')->paginate(15);

        return view('admin.cms.sections.index', compact('sections'));
    }

    public function create(): View
    {
        return view('admin.cms.sections.create');
    }

    public function store(CmsSectionRequest $request): RedirectResponse|JsonResponse
    {
        $section = CmsSection::create($request->validated());

        $this->auditService->logCreate('cms_section', $section);
        $this->cacheService->clearSectionCache($section->cms_page_id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Seção criada com sucesso!', 'data' => $section]);
        }

        return redirect()->route('admin.cms.sections.index')->with('success', 'Seção criada com sucesso!');
    }

    public function edit(CmsSection $cmsSection): View
    {
        return view('admin.cms.sections.edit', compact('cmsSection'));
    }

    public function update(CmsSectionRequest $request, CmsSection $cmsSection): RedirectResponse|JsonResponse
    {
        $oldValues = $cmsSection->toArray();
        $cmsSection->update($request->validated());

        $this->auditService->logUpdate('cms_section', $cmsSection, $oldValues, $cmsSection->fresh()->toArray());
        $this->cacheService->clearSectionCache($cmsSection->cms_page_id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Seção atualizada com sucesso!', 'data' => $cmsSection->fresh()]);
        }

        return redirect()->route('admin.cms.sections.index')->with('success', 'Seção atualizada com sucesso!');
    }

    public function destroy(CmsSection $cmsSection): RedirectResponse|JsonResponse
    {
        $pageId = $cmsSection->cms_page_id;
        $cmsSection->delete();

        $this->auditService->logDelete('cms_section', $cmsSection);
        $this->cacheService->clearSectionCache($pageId);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Seção excluída com sucesso!']);
        }

        return redirect()->route('admin.cms.sections.index')->with('success', 'Seção excluída com sucesso!');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:cms_sections,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            CmsSection::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        $page = CmsSection::find($request->items[0]['id']);

        if ($page) {
            $this->cacheService->clearSectionCache($page->cms_page_id);
        }

        return response()->json(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
    }
}
