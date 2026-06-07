<?php

namespace App\Services\Cms;

use App\Models\CmsPublicPage;
use App\Models\CmsPublicPageField;

class CmsSyncDefaultsService
{
    public function syncAll(): array
    {
        $stats = ['synced' => 0, 'skipped' => 0, 'total' => 0];

        $definitions = CmsPageDefinitions::pages();

        foreach ($definitions as $def) {
            $page = CmsPublicPage::where('page_key', $def['page_key'])->first();
            if (!$page) {
                continue;
            }

            foreach ($def['sections'] ?? [] as $sectionDef) {
                foreach ($sectionDef['fields'] ?? [] as $fieldDef) {
                    $field = CmsPublicPageField::where('page_id', $page->id)
                        ->where('section_key', $sectionDef['section_key'])
                        ->where('field_key', $fieldDef['field_key'])
                        ->first();

                    if (!$field) {
                        continue;
                    }

                    $stats['total']++;

                    if ($field->field_value !== null && $field->field_value !== '') {
                        $stats['skipped']++;
                        continue;
                    }

                    $field->update([
                        'default_value' => $fieldDef['default_value'] ?? $field->default_value,
                        'is_synced' => true,
                    ]);

                    $stats['synced']++;
                }
            }
        }

        return $stats;
    }
}
