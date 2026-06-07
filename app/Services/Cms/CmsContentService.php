<?php

namespace App\Services\Cms;

use App\Models\CmsOriginalPage;
use App\Models\CmsOriginalPageAuditLog;
use App\Models\CmsOriginalPageField;
use App\Models\CmsOriginalPageSeo;
use App\Models\CmsOriginalPageVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class CmsContentService
{
    public function get(string $pageKey, string $sectionKey, string $fieldKey, mixed $default = ''): string
    {
        if (!Schema::hasTable('cms_original_page_fields')) {
            return (string) $default;
        }

        $cacheKey = "cms_original_field_{$pageKey}_{$sectionKey}_{$fieldKey}";

        return Cache::remember($cacheKey, 600, function () use ($pageKey, $sectionKey, $fieldKey, $default) {
            $page = CmsOriginalPage::where('page_key', $pageKey)->where('is_active', true)->first();
            if (!$page) {
                return (string) $default;
            }

            $field = CmsOriginalPageField::where('page_id', $page->id)
                ->where('section_key', $sectionKey)
                ->where('field_key', $fieldKey)
                ->first();

            if (!$field) {
                return (string) $default;
            }

            $value = $field->resolvedValue();

            return $value !== '' ? $value : (string) $default;
        });
    }

    public function getHtml(string $pageKey, string $sectionKey, string $fieldKey, mixed $default = ''): string
    {
        return CmsSanitizer::clean($this->get($pageKey, $sectionKey, $fieldKey, $default));
    }

    public function getPageFields(string $pageKey): array
    {
        if (!Schema::hasTable('cms_original_page_fields')) {
            return [];
        }

        $cacheKey = "cms_original_page_fields_{$pageKey}";

        return Cache::remember($cacheKey, 600, function () use ($pageKey) {
            $page = CmsOriginalPage::where('page_key', $pageKey)->first();
            if (!$page) {
                return [];
            }

            $fields = [];
            foreach ($page->fields as $field) {
                $fields[$field->section_key][$field->field_key] = $field->resolvedValue();
            }

            return $fields;
        });
    }

    public function getSeo(string $pageKey): ?CmsOriginalPageSeo
    {
        if (!Schema::hasTable('cms_original_page_seo')) {
            return null;
        }

        $page = CmsOriginalPage::where('page_key', $pageKey)->first();

        return $page?->seo;
    }

    public function clearPageCache(CmsOriginalPage $page): void
    {
        Cache::forget("cms_original_page_fields_{$page->page_key}");

        foreach ($page->fields as $field) {
            Cache::forget("cms_original_field_{$page->page_key}_{$field->section_key}_{$field->field_key}");
        }

        Cache::forget("cms_original_seo_{$page->page_key}");
    }

    public function updateFields(CmsOriginalPage $page, array $fieldsData): void
    {
        $oldSnapshot = $this->buildSnapshot($page);

        foreach ($fieldsData as $fieldId => $value) {
            $field = CmsOriginalPageField::where('page_id', $page->id)->where('id', $fieldId)->first();
            if ($field && $field->is_editable) {
                $field->update([
                    'field_value' => $field->field_type === 'html'
                        ? CmsSanitizer::clean($value)
                        : $value,
                ]);
            }
        }

        $this->createVersion($page, $oldSnapshot, 'Atualização de conteúdo');
        $this->audit($page, 'update_fields', null, null, $oldSnapshot, $this->buildSnapshot($page));
        $this->clearPageCache($page);
    }

    public function updateSeo(CmsOriginalPage $page, array $seoData): void
    {
        $old = $page->seo?->toArray();

        CmsOriginalPageSeo::updateOrCreate(
            ['page_id' => $page->id],
            $seoData
        );

        $this->audit($page, 'update_seo', 'seo', $page->id, $old, $seoData);
        Cache::forget("cms_original_seo_{$page->page_key}");
    }

    public function buildSnapshot(CmsOriginalPage $page): array
    {
        return [
            'fields' => $page->fields()->get()->map(fn ($f) => [
                'id' => $f->id,
                'section_key' => $f->section_key,
                'field_key' => $f->field_key,
                'field_value' => $f->field_value,
            ])->toArray(),
            'seo' => $page->seo?->toArray(),
        ];
    }

    public function createVersion(CmsOriginalPage $page, array $snapshot, string $summary): void
    {
        CmsOriginalPageVersion::create([
            'page_id' => $page->id,
            'user_id' => Auth::id(),
            'snapshot' => $snapshot,
            'change_summary' => $summary,
        ]);
    }

    public function audit(
        CmsOriginalPage $page,
        string $action,
        ?string $entityType,
        ?int $entityId,
        mixed $oldValues,
        mixed $newValues
    ): void {
        CmsOriginalPageAuditLog::create([
            'page_id' => $page->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
