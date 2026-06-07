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
use App\Models\CmsMenu;
use App\Models\CmsMenuItem;
use App\Models\CmsOriginalPage;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use App\Services\Cms\CmsMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsMenuController extends Controller
{
    protected CmsMenuService $menuService;
    protected CmsCacheService $cacheService;
    protected CmsAuditService $auditService;

    public function __construct(
        CmsMenuService $menuService,
        CmsCacheService $cacheService,
        CmsAuditService $auditService
    ) {
        $this->menuService = $menuService;
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;

        $this->middleware('can:cms.menus.view')->only(['index']);
        $this->middleware('can:cms.menus.create')->only(['store']);
        $this->middleware('can:cms.menus.edit')->only(['update', 'buildPublicPages']);
        $this->middleware('can:cms.menus.delete')->only(['destroy']);
    }

    public function index(Request $request): View
    {
        $menus = CmsMenu::orderBy('sort_order')->get();
        $selectedMenuId = $request->integer('menu_id', $menus->isNotEmpty() ? $menus->first()->id : 0);
        $selectedMenu = CmsMenu::with(['items' => function ($q) {
            $q->orderBy('sort_order');
        }])->find($selectedMenuId);

        $menuItems = $selectedMenu ? $selectedMenu->items : collect();
        $allItems = $menuItems;
        $publicPages = CmsOriginalPage::where('is_active', true)
            ->where('is_editable', true)
            ->orderBy('admin_label')
            ->get();

        return view('admin.cms.menus.index', compact('menus', 'selectedMenu', 'menuItems', 'allItems', 'publicPages'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:200|unique:cms_menus,slug',
            'description' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['created_by'] = auth()->id();

        $menu = CmsMenu::create($validated);

        $this->auditService->logCreate('cms_menu', $menu);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Menu criado com sucesso!', 'data' => $menu]);
        }

        return redirect()->route('admin.cms.menus.index')->with('success', 'Menu criado com sucesso!');
    }

    public function update(Request $request, CmsMenu $cmsMenu): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:200|unique:cms_menus,slug,' . $cmsMenu->id,
            'description' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $oldValues = $cmsMenu->toArray();
        $cmsMenu->update($validated);

        $this->auditService->logUpdate('cms_menu', $cmsMenu, $oldValues, $cmsMenu->fresh()->toArray());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Menu atualizado com sucesso!', 'data' => $cmsMenu->fresh()]);
        }

        return redirect()->route('admin.cms.menus.index')->with('success', 'Menu atualizado com sucesso!');
    }

    public function destroy(CmsMenu $cmsMenu): RedirectResponse|JsonResponse
    {
        $cmsMenu->items()->delete();
        $cmsMenu->delete();

        $this->auditService->logDelete('cms_menu', $cmsMenu);
        $this->cacheService->clearMenuCache();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Menu excluído com sucesso!']);
        }

        return redirect()->route('admin.cms.menus.index')->with('success', 'Menu excluído com sucesso!');
    }

    public function buildPublicPages(Request $request): RedirectResponse
    {
        $menuId = $request->integer('cms_menu_id', 0);
        $menu = CmsMenu::find($menuId);

        if (!$menu) {
            $menu = CmsMenu::firstOrCreate(
                ['slug' => 'menu-paginas-publicas'],
                [
                    'name' => 'Menu de Páginas Públicas',
                    'description' => 'Menu gerado automaticamente com páginas públicas reais.',
                    'location' => 'header',
                    'is_active' => true,
                    'sort_order' => 0,
                    'created_by' => auth()->id(),
                ]
            );
        }

        $existingUrls = $menu->items()->pluck('url')->filter()->toArray();
        $currentMaxOrder = $menu->items()->max('sort_order');
        $pages = CmsOriginalPage::where('is_active', true)
            ->where('is_editable', true)
            ->orderBy('admin_label')
            ->get();

        foreach ($pages as $index => $page) {
            $url = $page->publicUrl();
            if (!$url || in_array($url, $existingUrls, true)) {
                continue;
            }

            $menu->items()->create([
                'title' => $page->admin_label ?? $page->title,
                'url' => $url,
                'icon' => null,
                'target' => '_self',
                'is_active' => true,
                'sort_order' => ($currentMaxOrder !== null ? $currentMaxOrder + $index + 1 : $index),
                'created_by' => auth()->id(),
            ]);
        }

        $this->cacheService->clearMenuCache($menu->location);

        return redirect()->route('admin.cms.menus.index', ['menu_id' => $menu->id])
            ->with('success', 'Menu montado com as páginas públicas reais.');
    }

    public function getMenuItems(int $menuId): JsonResponse
    {
        $menu = CmsMenu::with('items.children')->findOrFail($menuId);

        return response()->json(['success' => true, 'data' => $menu->items]);
    }

    public function reorderItems(Request $request): JsonResponse
    {
        $items = $request->input('items', []);

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'Nenhum item para reordenar.'], 400);
        }

        $flatOrder = [];
        $this->flattenTreeForReorder($items, $flatOrder);

        if (!empty($flatOrder)) {
            foreach ($flatOrder as $index => $itemInfo) {
                CmsMenuItem::where('id', $itemInfo['id'])
                    ->update([
                        'sort_order' => $index,
                        'parent_id' => $itemInfo['parent_id'],
                    ]);
            }
        }

        $this->cacheService->clearMenuCache();

        return response()->json(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
    }

    protected function flattenTreeForReorder(array $items, array &$result, ?int $parentId = null): void
    {
        foreach ($items as $item) {
            $itemId = is_array($item) ? ($item['id'] ?? null) : $item;
            $children = is_array($item) ? ($item['children'] ?? []) : [];

            if ($itemId) {
                $result[] = [
                    'id' => $itemId,
                    'parent_id' => $parentId,
                ];
            }

            if (!empty($children)) {
                $this->flattenTreeForReorder($children, $result, $itemId);
            }
        }
    }

    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cms_menu_id' => 'required|integer|exists:cms_menus,id',
            'parent_id' => 'nullable|integer|exists:cms_menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'target' => 'nullable|in:_self,_blank',
            'is_active' => 'nullable|boolean',
            'css_class' => 'nullable|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['created_by'] = auth()->id();

        $item = CmsMenuItem::create($validated);

        $this->cacheService->clearMenuCache();

        return response()->json(['success' => true, 'message' => 'Item adicionado com sucesso!', 'data' => $item]);
    }

    public function updateItem(Request $request, CmsMenuItem $cmsMenuItem): JsonResponse
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|integer|exists:cms_menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'target' => 'nullable|in:_self,_blank',
            'is_active' => 'nullable|boolean',
            'css_class' => 'nullable|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $cmsMenuItem->update($validated);

        $this->cacheService->clearMenuCache();

        return response()->json(['success' => true, 'message' => 'Item atualizado com sucesso!', 'data' => $cmsMenuItem->fresh()]);
    }

    public function deleteItem(CmsMenuItem $cmsMenuItem): JsonResponse
    {
        $cmsMenuItem->delete();

        $this->cacheService->clearMenuCache();

        return response()->json(['success' => true, 'message' => 'Item excluído com sucesso!']);
    }
}
