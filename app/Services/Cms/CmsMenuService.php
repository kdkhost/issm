<?php

namespace App\Services\Cms;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use App\Models\CmsMenu;
use App\Models\CmsMenuItem;
use App\Services\Cms\CmsCacheService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CmsMenuService
{
    protected CmsCacheService $cacheService;

    public function __construct(CmsCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getMenu(string $location): ?CmsMenu
    {
        $key = "cms.menu.{$location}";

        return $this->cacheService->remember($key, function () use ($location) {
            return CmsMenu::where('location', $location)
                ->where('active', true)
                ->with(['items' => function ($query) {
                    $query->where('active', true)
                        ->orderBy('parent_id')
                        ->orderBy('order');
                }])
                ->first();
        });
    }

    public function renderMenu(string $location): string
    {
        $menu = $this->getMenu($location);

        if (!$menu || $menu->items->isEmpty()) {
            return '';
        }

        $tree = $this->buildTree($menu->items);

        return '<nav class="cms-menu cms-menu-' . e($location) . '">'
            . $this->renderTree($tree)
            . '</nav>';
    }

    public function getMenuTree(int $menuId): Collection
    {
        $items = CmsMenuItem::where('menu_id', $menuId)
            ->where('active', true)
            ->orderBy('order')
            ->get();

        return $this->buildTree($items);
    }

    public function buildBreadcrumbs(string $currentUrl): array
    {
        $breadcrumbs = [
            ['title' => 'Home', 'url' => url('/'), 'active' => false],
        ];

        $path = parse_url($currentUrl, PHP_URL_PATH);
        $segments = array_filter(explode('/', $path));

        $accumulated = '';
        $lastKey = array_key_last($segments);

        foreach ($segments as $key => $segment) {
            $accumulated .= '/' . $segment;
            $title = $this->resolveBreadcrumbTitle($segment);

            $breadcrumbs[] = [
                'title' => $title,
                'url' => url($accumulated),
                'active' => ($key === $lastKey),
            ];
        }

        if (count($breadcrumbs) === 1) {
            $breadcrumbs[0]['active'] = true;
        }

        return $breadcrumbs;
    }

    public function createMenu(array $data): CmsMenu
    {
        $menu = CmsMenu::create([
            'name' => $data['name'],
            'location' => $data['location'],
            'description' => $data['description'] ?? null,
            'active' => $data['active'] ?? true,
        ]);

        if (!empty($data['items'])) {
            foreach ($data['items'] as $order => $itemData) {
                $menu->items()->create([
                    'label' => $itemData['label'],
                    'url' => $itemData['url'] ?? null,
                    'route' => $itemData['route'] ?? null,
                    'params' => $itemData['params'] ?? null,
                    'icon' => $itemData['icon'] ?? null,
                    'target' => $itemData['target'] ?? '_self',
                    'parent_id' => $itemData['parent_id'] ?? null,
                    'order' => $order,
                    'active' => $itemData['active'] ?? true,
                ]);
            }
        }

        $this->cacheService->clearMenuCache($menu->location);

        return $menu->load('items');
    }

    public function updateMenu(CmsMenu $menu, array $data): CmsMenu
    {
        $menu->update([
            'name' => $data['name'] ?? $menu->name,
            'location' => $data['location'] ?? $menu->location,
            'description' => $data['description'] ?? $menu->description,
            'active' => $data['active'] ?? $menu->active,
        ]);

        if (isset($data['items'])) {
            $menu->items()->delete();

            foreach ($data['items'] as $order => $itemData) {
                $menu->items()->create([
                    'label' => $itemData['label'],
                    'url' => $itemData['url'] ?? null,
                    'route' => $itemData['route'] ?? null,
                    'params' => $itemData['params'] ?? null,
                    'icon' => $itemData['icon'] ?? null,
                    'target' => $itemData['target'] ?? '_self',
                    'parent_id' => $itemData['parent_id'] ?? null,
                    'order' => $order,
                    'active' => $itemData['active'] ?? true,
                ]);
            }
        }

        $this->cacheService->clearMenuCache($menu->location);

        return $menu->load('items');
    }

    public function reorderItems(int $menuId, array $order): void
    {
        foreach ($order as $index => $itemId) {
            CmsMenuItem::where('id', $itemId)
                ->where('menu_id', $menuId)
                ->update(['order' => $index]);
        }

        $menu = CmsMenu::find($menuId);
        if ($menu) {
            $this->cacheService->clearMenuCache($menu->location);
        }
    }

    protected function buildTree(Collection $items, ?int $parentId = null): Collection
    {
        return $items->where('parent_id', $parentId)
            ->map(function ($item) use ($items) {
                $children = $this->buildTree($items, $item->id);
                $item->children = $children;
                return $item;
            })
            ->values();
    }

    protected function renderTree(Collection $items): string
    {
        if ($items->isEmpty()) {
            return '';
        }

        $html = '<ul>';

        foreach ($items as $item) {
            $url = $item->route ? route($item->route) : url($item->url);
            $active = url()->current() === $url ? ' class="active"' : '';
            $target = $item->target ? ' target="' . e($item->target) . '"' : '';
            $icon = $item->icon ? '<i class="' . e($item->icon) . '"></i> ' : '';

            $html .= '<li' . $active . '>';
            $html .= '<a href="' . e($url) . '"' . $target . '>' . $icon . e($item->label) . '</a>';

            if ($item->children->isNotEmpty()) {
                $html .= $this->renderTree($item->children);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    protected function resolveBreadcrumbTitle(string $segment): string
    {
        $page = \App\Models\CmsPage::where('slug', $segment)->first();

        if ($page) {
            return $page->title;
        }

        $titles = [
            'noticias' => 'Notícias',
            'projetos' => 'Projetos',
            'transparencia' => 'Transparência',
            'contato' => 'Contato',
            'galeria' => 'Galeria',
            'ods' => 'ODS',
            'sobre' => 'Sobre',
            'admin' => 'Painel Administrativo',
        ];

        return $titles[$segment] ?? ucfirst(str_replace(['-', '_'], ' ', $segment));
    }
}
