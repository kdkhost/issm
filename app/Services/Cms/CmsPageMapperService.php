<?php

namespace App\Services\Cms;

use App\Models\CmsPublicPage;
use App\Models\CmsPublicPageField;
use App\Models\CmsPublicPageSection;
use Illuminate\Support\Facades\Route;

class CmsPageMapperService
{
    public function mapAll(): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'review' => 0, 'total' => 0];
        $definitions = CmsPageDefinitions::pages();
        $foundRouteNames = [];

        foreach ($definitions as $def) {
            $routeExists = $this->routeExists($def['route_name'] ?? null);
            if ($routeExists) {
                $foundRouteNames[] = $def['route_name'];
            }

            $page = CmsPublicPage::withTrashed()->where('page_key', $def['page_key'])->first();

            $data = [
                'route_name' => $def['route_name'] ?? null,
                'route_uri' => $def['route_uri'] ?? null,
                'controller' => $def['controller'] ?? null,
                'method' => $def['method'] ?? 'index',
                'view_path' => $def['view_path'] ?? null,
                'title' => $def['title'],
                'admin_label' => $def['admin_label'],
                'is_editable' => $def['is_editable'] ?? true,
                'is_active' => true,
                'sort_order' => $def['sort_order'] ?? 0,
                'needs_review' => !$routeExists,
                'last_mapped_at' => now(),
            ];

            if ($page) {
                if ($page->trashed()) {
                    $page->restore();
                }
                $page->update($data);
                $stats['updated']++;
            } else {
                $page = CmsPublicPage::create(array_merge($data, ['page_key' => $def['page_key']]));
                $stats['created']++;
            }

            if (!$routeExists) {
                $stats['review']++;
            }

            $this->syncSectionsAndFields($page, $def['sections'] ?? []);
            $stats['total']++;
        }

        $this->markOrphanPagesForReview($foundRouteNames);

        return $stats;
    }

    private function routeExists(?string $routeName): bool
    {
        if (!$routeName) {
            return false;
        }

        return Route::has($routeName);
    }

    private function syncSectionsAndFields(CmsPublicPage $page, array $sections): void
    {
        foreach ($sections as $sectionDef) {
            CmsPublicPageSection::updateOrCreate(
                ['page_id' => $page->id, 'section_key' => $sectionDef['section_key']],
                [
                    'section_label' => $sectionDef['section_label'],
                    'sort_order' => $sectionDef['sort_order'] ?? 0,
                    'is_active' => true,
                ]
            );

            foreach ($sectionDef['fields'] ?? [] as $fieldDef) {
                CmsPublicPageField::updateOrCreate(
                    [
                        'page_id' => $page->id,
                        'section_key' => $sectionDef['section_key'],
                        'field_key' => $fieldDef['field_key'],
                    ],
                    [
                        'field_type' => $fieldDef['field_type'] ?? 'text',
                        'field_label' => $fieldDef['field_label'],
                        'default_value' => $fieldDef['default_value'] ?? '',
                        'is_required' => $fieldDef['is_required'] ?? false,
                        'is_editable' => $fieldDef['is_editable'] ?? true,
                        'sort_order' => $fieldDef['sort_order'] ?? 0,
                        'validation_rules' => $fieldDef['validation_rules'] ?? null,
                        'help_text' => $fieldDef['help_text'] ?? null,
                    ]
                );
            }
        }
    }

    private function markOrphanPagesForReview(array $foundRouteNames): void
    {
        CmsPublicPage::whereNotIn('route_name', $foundRouteNames)
            ->whereNotNull('route_name')
            ->update(['needs_review' => true]);
    }
}
