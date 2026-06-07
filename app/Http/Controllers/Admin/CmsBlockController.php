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
use App\Http\Requests\Admin\CmsBlockRequest;
use App\Models\CmsBlock;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use App\Services\Cms\CmsVersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsBlockController extends Controller
{
    protected CmsCacheService $cacheService;
    protected CmsAuditService $auditService;
    protected CmsVersionService $versionService;

    public function __construct(
        CmsCacheService $cacheService,
        CmsAuditService $auditService,
        CmsVersionService $versionService
    ) {
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;
        $this->versionService = $versionService;

        $this->middleware('can:cms.blocks.view')->only(['index', 'show']);
        $this->middleware('can:cms.blocks.create')->only(['create', 'store']);
        $this->middleware('can:cms.blocks.edit')->only(['edit', 'update']);
        $this->middleware('can:cms.blocks.delete')->only(['destroy']);
    }

    public function index(Request $request): View
    {
        $query = CmsBlock::with('section', 'page');

        if ($pageId = $request->get('page_id')) {
            $query->where('cms_page_id', $pageId);
        }

        if ($sectionId = $request->get('section_id')) {
            $query->where('cms_section_id', $sectionId);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $blocks = $query->orderBy('sort_order')->paginate(15);

        return view('admin.cms.blocks.index', compact('blocks'));
    }

    public function create(): View
    {
        return view('admin.cms.blocks.create');
    }

    public function store(CmsBlockRequest $request): RedirectResponse|JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $block = CmsBlock::create($data);

        $this->versionService->createVersion($block, 'Bloco criado');
        $this->auditService->logCreate('cms_block', $block);
        $this->cacheService->clearBlockCache($block->cms_section_id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Bloco criado com sucesso!', 'data' => $block]);
        }

        return redirect()->route('admin.cms.blocks.index')->with('success', 'Bloco criado com sucesso!');
    }

    public function edit(CmsBlock $cmsBlock): View
    {
        return view('admin.cms.blocks.edit', compact('cmsBlock'));
    }

    public function update(CmsBlockRequest $request, CmsBlock $cmsBlock): RedirectResponse|JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $oldValues = $cmsBlock->toArray();
        $cmsBlock->update($data);

        $this->versionService->createVersion($cmsBlock, 'Bloco atualizado');
        $this->auditService->logUpdate('cms_block', $cmsBlock, $oldValues, $cmsBlock->fresh()->toArray());
        $this->cacheService->clearBlockCache($cmsBlock->cms_section_id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Bloco atualizado com sucesso!', 'data' => $cmsBlock->fresh()]);
        }

        return redirect()->route('admin.cms.blocks.index')->with('success', 'Bloco atualizado com sucesso!');
    }

    public function destroy(CmsBlock $cmsBlock): RedirectResponse|JsonResponse
    {
        $sectionId = $cmsBlock->cms_section_id;
        $cmsBlock->delete();

        $this->auditService->logDelete('cms_block', $cmsBlock);
        $this->cacheService->clearBlockCache($sectionId);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Bloco excluído com sucesso!']);
        }

        return redirect()->route('admin.cms.blocks.index')->with('success', 'Bloco excluído com sucesso!');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:cms_blocks,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            CmsBlock::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
    }

    public function toggleStatus(CmsBlock $cmsBlock): RedirectResponse|JsonResponse
    {
        $cmsBlock->update(['is_active' => !$cmsBlock->is_active]);
        $cmsBlock->refresh();

        $this->cacheService->clearBlockCache($cmsBlock->cms_section_id);

        $message = $cmsBlock->is_active ? 'Bloco ativado com sucesso!' : 'Bloco desativado com sucesso!';

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'is_active' => $cmsBlock->is_active]);
        }

        return redirect()->route('admin.cms.blocks.index')->with('success', $message);
    }
}
