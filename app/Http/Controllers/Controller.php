<?php

namespace App\Http\Controllers;

use App\Models\CmsBlock;
use App\Models\CmsPage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function loadCmsPage(string $slug): array
    {
        $cmsPage = CmsPage::active()->published()->where('slug', $slug)->first();
        $cmsSections = collect();
        $cmsBlocks = collect();

        if ($cmsPage) {
            $cmsSections = $cmsPage->sections()
                ->active()->ordered()
                ->with(['blocks' => fn($q) => $q->active()->published()->ordered()])
                ->get();
        }

        return compact('cmsPage', 'cmsSections', 'cmsBlocks');
    }

    protected function extractCmsContent($sections): array
    {
        $cms = [];
        if ($sections && $sections->isNotEmpty()) {
            foreach ($sections as $section) {
                $block = $section->blocks->first();
                $key = Str::slug($section->name, '_');
                $cms[$key . '_title'] = $block->title ?? $section->name;
                $cms[$key . '_subtitle'] = $block->subtitle ?? '';
                $cms[$key . '_content'] = $block->content ?? '';
            }
        }
        return $cms;
    }
}
