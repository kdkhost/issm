<?php

namespace App\Services\Cms;

use App\Models\CmsPublicPage;
use App\Models\CmsPublicPageAuditLog;
use App\Models\CmsPublicPageField;
use App\Models\CmsPublicPageSeo;
use App\Models\CmsPublicPageVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class CmsContentService
{
    public function get(string $pageKey, string $sectionKey, string $fieldKey, mixed $default = ''): string
    {
        if (!Schema::hasTable('cms_public_page_fields')) {
            return (string) $default;
        }

        $cacheKey = "cms_field_{$pageKey}_{$sectionKey}_{$fieldKey}";

        return Cache::remember($cacheKey, 600, function () use ($pageKey, $sectionKey, $fieldKey, $default) {
            $page = CmsPublicPage::where('page_key', $pageKey)->where('is_active', true)->first();
            if (!$page) {
                return (string) $default;
            }

            $field = CmsPublicPageField::where('page_id', $page->id)
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
        if (!Schema::hasTable('cms_public_page_fields')) {
            return [];
        }

        $cacheKey = "cms_page_fields_{$pageKey}";

        return Cache::remember($cacheKey, 600, function () use ($pageKey) {
            $page = CmsPublicPage::where('page_key', $pageKey)->first();
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

    public function getSeo(string $pageKey): ?CmsPublicPageSeo
    {
        if (!Schema::hasTable('cms_public_page_seo')) {
            return null;
        }

        $page = CmsPublicPage::where('page_key', $pageKey)->first();

        return $page?->seo;
    }

    public function clearPageCache(CmsPublicPage $page): void
    {
        Cache::forget("cms_page_fields_{$page->page_key}");

        foreach ($page->fields as $field) {
            Cache::forget("cms_field_{$page->page_key}_{$field->section_key}_{$field->field_key}");
        }

        Cache::forget("cms_seo_{$page->page_key}");
    }

    public function updateFields(CmsPublicPage $page, array $fieldsData): void
    {
        $oldSnapshot = $this->buildSnapshot($page);

        foreach ($fieldsData as $fieldId => $value) {
            $field = CmsPublicPageField::where('page_id', $page->id)->where('id', $fieldId)->first();
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

    public function updateSeo(CmsPublicPage $page, array $seoData): void
    {
        $old = $page->seo?->toArray();

        $seoData['seo_score'] = $this->calculateSeoScore($seoData);

        CmsPublicPageSeo::updateOrCreate(
            ['page_id' => $page->id],
            $seoData
        );

        $this->audit($page, 'update_seo', 'seo', $page->id, $old, $seoData);
        Cache::forget("cms_seo_{$page->page_key}");
    }

    public function calculateSeoScore(array $data): int
    {
        $score = 0;
        $mt = $data['meta_title'] ?? '';
        $md = $data['meta_description'] ?? '';
        $ot = $data['og_title'] ?? '';
        $od = $data['og_description'] ?? '';
        $oi = $data['og_image'] ?? '';
        $mk = $data['meta_keywords'] ?? '';
        $cu = $data['canonical_url'] ?? '';
        $rm = $data['robots_meta'] ?? '';

        // Meta Title: presente (15) + tamanho ideal 50-60 (10)
        if (trim($mt)) { $score += 15; }
        $mtLen = mb_strlen($mt);
        if ($mtLen >= 50 && $mtLen <= 60) { $score += 10; }

        // Meta Description: presente (15) + tamanho ideal 120-160 (10)
        if (trim($md)) { $score += 15; }
        $mdLen = mb_strlen($md);
        if ($mdLen >= 120 && $mdLen <= 160) { $score += 10; }

        // OG Title (10)
        if (trim($ot)) { $score += 10; }

        // OG Description (10)
        if (trim($od)) { $score += 10; }

        // OG Image (15)
        if (trim($oi)) { $score += 15; }

        // Keywords (5)
        if (trim($mk)) { $score += 5; }

        // Canonical URL (5)
        if (trim($cu)) { $score += 5; }

        // Robots Meta (5)
        if (trim($rm)) { $score += 5; }

        return min($score, 100);
    }

    public function buildSnapshot(CmsPublicPage $page): array
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

    public function createVersion(CmsPublicPage $page, array $snapshot, string $summary): void
    {
        CmsPublicPageVersion::create([
            'page_id' => $page->id,
            'user_id' => Auth::id(),
            'snapshot' => $snapshot,
            'change_summary' => $summary,
        ]);
    }

    public function audit(
        CmsPublicPage $page,
        string $action,
        ?string $entityType,
        ?int $entityId,
        mixed $oldValues,
        mixed $newValues
    ): void {
        CmsPublicPageAuditLog::create([
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
