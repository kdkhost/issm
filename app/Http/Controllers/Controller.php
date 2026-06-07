<?php

namespace App\Http\Controllers;

use App\Models\CmsBlock;
use App\Models\CmsPage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

            $cmsBlocks = CmsBlock::where('cms_page_id', $cmsPage->id)
                ->active()->published()->ordered()
                ->get();
        }

        return compact('cmsPage', 'cmsSections', 'cmsBlocks');
    }
}
