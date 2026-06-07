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
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\CmsPageSeo;
use App\Models\CmsSection;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use App\Services\Cms\CmsPageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $sections = $cmsPage->sections()->orderBy('sort_order')->with('blocks')->get();
        $seo = CmsPageSeo::firstOrNew(['cms_page_id' => $cmsPage->id]);
        $menus = CmsMenu::with(['items' => function ($q) { $q->orderBy('sort_order'); }])->get();

        return view('admin.cms.pages.edit', compact('cmsPage', 'sections', 'seo', 'menus'));
    }

    public function update(CmsPageRequest $request, CmsPage $cmsPage): RedirectResponse|JsonResponse
    {
        $data = $request->validated();

        if ($request->has('settings') && is_string($request->input('settings'))) {
            $data['settings'] = json_decode($request->input('settings'), true);
        } elseif ($request->has('settings')) {
            $data['settings'] = $request->input('settings');
        }

        $page = $this->pageService->updatePage($cmsPage, $data);

        if ($request->has('seo')) {
            CmsPageSeo::updateOrCreate(
                ['cms_page_id' => $cmsPage->id],
                array_merge($request->input('seo'), ['is_active' => true])
            );
        }

        $this->cacheService->clearPageCache($cmsPage->slug);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Página atualizada com sucesso!', 'data' => $page]);
        }

        return redirect()->route('admin.cms.pages.edit', $cmsPage)->with('success', 'Página atualizada com sucesso!');
    }

    public function updateSettings(Request $request, CmsPage $cmsPage): JsonResponse
    {
        $settings = $request->input('settings', []);
        $existing = $cmsPage->settings ? (array)$cmsPage->settings : [];
        $cmsPage->update(['settings' => array_merge($existing, $settings)]);

        $this->cacheService->clearPageCache($cmsPage->slug);

        return response()->json(['success' => true, 'message' => 'Configurações salvas!']);
    }

    public function uploadBanner(Request $request, CmsPage $cmsPage): JsonResponse
    {
        $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120']);

        if (!$request->hasFile('image')) {
            return response()->json(['success' => false, 'message' => 'Nenhum arquivo enviado.'], 400);
        }

        $file = $request->file('image');
        $filename = 'page_' . $cmsPage->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/cms/banners', $filename);
        $url = asset('storage/cms/banners/' . rawurlencode($filename));

        return response()->json(['success' => true, 'url' => $url, 'filename' => $filename]);
    }

    public function saveSection(Request $request, CmsPage $cmsPage): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|integer|exists:cms_sections,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'template' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if (!empty($validated['id'])) {
            $section = CmsSection::findOrFail($validated['id']);
            $section->update($validated);
        } else {
            $maxOrder = $cmsPage->sections()->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
            $section = $cmsPage->sections()->create($validated);
        }

        $this->cacheService->clearPageCache($cmsPage->slug);

        return response()->json(['success' => true, 'message' => 'Seção salva!', 'data' => $section->load('blocks')]);
    }

    public function deleteSection(CmsSection $cmsSection): JsonResponse
    {
        $cmsSection->blocks()->delete();
        $cmsSection->delete();

        return response()->json(['success' => true, 'message' => 'Seção excluída!']);
    }

    public function reorderSections(Request $request, CmsPage $cmsPage): JsonResponse
    {
        $items = $request->input('items', []);
        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                CmsSection::where('id', $item['id'])->update(['sort_order' => $item['order']]);
            }
        });

        $this->cacheService->clearPageCache($cmsPage->slug);

        return response()->json(['success' => true, 'message' => 'Ordem atualizada!']);
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
