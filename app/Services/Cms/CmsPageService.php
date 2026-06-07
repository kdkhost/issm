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

use App\Models\CmsPage;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use App\Services\Cms\CmsVersionService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CmsPageService
{
    protected CmsCacheService $cacheService;
    protected CmsVersionService $versionService;
    protected CmsAuditService $auditService;

    public function __construct(
        CmsCacheService $cacheService,
        CmsVersionService $versionService,
        CmsAuditService $auditService
    ) {
        $this->cacheService = $cacheService;
        $this->versionService = $versionService;
        $this->auditService = $auditService;
    }

    public function getBySlug(string $slug): ?CmsPage
    {
        $key = "cms.page.{$slug}";

        return $this->cacheService->remember($key, function () use ($slug) {
            return CmsPage::where('slug', $slug)
                ->with(['sections.blocks', 'seo'])
                ->first();
        });
    }

    public function getActivePages(): Collection
    {
        return $this->cacheService->remember('cms.pages.active', function () {
            return CmsPage::where('is_active', true)
                ->where('status', 'published')
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get();
        });
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return CmsPage::orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function createPage(array $data): CmsPage
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            $data['slug'] = $this->ensureUniqueSlug($data['slug']);

            $page = CmsPage::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'is_active' => $data['is_active'] ?? false,
                'sort_order' => $data['sort_order'] ?? 0,
                'template' => $data['template'] ?? 'default',
                'published_at' => ($data['status'] ?? 'draft') === 'published' ? now() : null,
            ]);

            $this->versionService->createVersion($page, 'Página criada');
            $this->auditService->logCreate('cms_page', $page);
            $this->cacheService->clearPageCache($page->slug);

            return $page;
        });
    }

    public function updatePage(CmsPage $page, array $data): CmsPage
    {
        return DB::transaction(function () use ($page, $data) {
            $oldValues = $page->toArray();

            if (isset($data['slug']) && $data['slug'] !== $page->slug) {
                $data['slug'] = $this->ensureUniqueSlug($data['slug'], $page->id);
            }

            $page->update([
                'title' => $data['title'] ?? $page->title,
                'slug' => $data['slug'] ?? $page->slug,
                'content' => $data['content'] ?? $page->content,
                'status' => $data['status'] ?? $page->status,
                'is_active' => $data['is_active'] ?? $page->is_active,
                'sort_order' => $data['sort_order'] ?? $page->sort_order,
                'template' => $data['template'] ?? $page->template,
            ]);

            if (($data['status'] ?? $page->status) === 'published' && !$page->published_at) {
                $page->update(['published_at' => now()]);
            }

            $page->refresh();

            $this->versionService->createVersion($page, 'Página atualizada');
            $this->auditService->logUpdate('cms_page', $page, $oldValues, $page->toArray());
            $this->cacheService->clearPageCache($page->slug);

            return $page;
        });
    }

    public function deletePage(CmsPage $page): bool
    {
        return DB::transaction(function () use ($page) {
            $slug = $page->slug;

            $page->sections()->delete();
            $page->seo()->delete();

            $result = $page->delete();

            $this->auditService->logDelete('cms_page', $page);
            $this->cacheService->clearPageCache($slug);

            return $result;
        });
    }

    public function publishPage(CmsPage $page): CmsPage
    {
        return DB::transaction(function () use ($page) {
            $page->update([
                'status' => 'published',
                'is_active' => true,
                'published_at' => $page->published_at ?? now(),
            ]);

            $page->refresh();

            $this->versionService->createVersion($page, 'Página publicada');
            $this->auditService->logPublish('cms_page', $page);
            $this->cacheService->clearPageCache($page->slug);

            return $page;
        });
    }

    public function archivePage(CmsPage $page): CmsPage
    {
        return DB::transaction(function () use ($page) {
            $page->update([
                'status' => 'archived',
                'is_active' => false,
            ]);

            $page->refresh();

            $this->versionService->createVersion($page, 'Página arquivada');
            $this->auditService->logArchive('cms_page', $page);
            $this->cacheService->clearPageCache($page->slug);

            return $page;
        });
    }

    public function duplicatePage(CmsPage $page): CmsPage
    {
        return DB::transaction(function () use ($page) {
            $duplicated = CmsPage::create([
                'title' => $page->title . ' (cópia)',
                'slug' => $this->ensureUniqueSlug(Str::slug($page->title . '-copia')),
                'content' => $page->content,
                'status' => 'draft',
                'is_active' => false,
                'sort_order' => 0,
                'template' => $page->template,
            ]);

            foreach ($page->sections as $section) {
                $newSection = $duplicated->sections()->create([
                    'title' => $section->title,
                    'template' => $section->template,
                    'sort_order' => $section->sort_order,
                    'is_active' => $section->is_active,
                ]);

                foreach ($section->blocks as $block) {
                    $newSection->blocks()->create([
                        'type' => $block->type,
                        'content' => $block->content,
                        'settings' => $block->settings,
                        'sort_order' => $block->sort_order,
                        'is_active' => $block->is_active,
                    ]);
                }
            }

            if ($page->seo) {
                $duplicated->seo()->create($page->seo->toArray());
            }

            $this->versionService->createVersion($duplicated, 'Página duplicada de #' . $page->id);
            $this->auditService->logCreate('cms_page', $duplicated);

            return $duplicated;
        });
    }

    public function toggleStatus(CmsPage $page): bool
    {
        $page->update(['is_active' => !$page->is_active]);
        $page->refresh();

        $this->cacheService->clearPageCache($page->slug);

        return $page->is_active;
    }

    public function getPageWithRelations(CmsPage $page): CmsPage
    {
        return $page->load([
            'sections' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            },
            'sections.blocks' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            },
            'seo',
            'versions' => function ($query) {
                $query->orderBy('id', 'desc')->take(10);
            },
        ]);
    }

    public function getAllSlugs(): Collection
    {
        return $this->cacheService->remember('cms.pages.all_slugs', function () {
            return CmsPage::select('id', 'slug', 'title', 'is_active', 'status')
                ->orderBy('slug')
                ->get()
                ->mapWithKeys(fn ($page) => [$page->slug => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'is_active' => $page->is_active,
                    'status' => $page->status,
                ]]);
        });
    }

    public function searchPages(string $term): Collection
    {
        return CmsPage::where('title', 'like', "%{$term}%")
            ->orWhere('slug', 'like', "%{$term}%")
            ->orWhere('content', 'like', "%{$term}%")
            ->orWhere('excerpt', 'like', "%{$term}%")
            ->orderBy('title')
            ->get();
    }

    protected function ensureUniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $original = $slug;
        $counter = 1;

        while (true) {
            $query = CmsPage::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
