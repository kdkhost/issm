<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCmsPublicPageRequest;
use App\Http\Requests\Admin\UpdateCmsPublicPageSeoRequest;
use App\Models\CmsOriginalPage;
use App\Services\Cms\CmsContentService;
use Illuminate\Http\Request;

class CmsPublicPageController extends Controller
{
    public function index()
    {
        $pages = CmsOriginalPage::withCount('fields')
            ->where('is_active', true)
            ->where('is_editable', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function (CmsOriginalPage $page) {
                $page->pending_count = $page->pendingFieldsCount();

                return $page;
            });

        return view('admin.cms-public-pages.index', compact('pages'));
    }

    public function edit(CmsOriginalPage $cmsPublicPage)
    {
        if (!$cmsPublicPage->is_editable) {
            return redirect()
                ->route('admin.cms-original-pages.index')
                ->with('error', 'Esta página é um modelo dinâmico e não pode ser editada por aqui.');
        }

        $cmsPublicPage->load(['sections', 'fields']);

        $fieldsBySection = $cmsPublicPage->fields->groupBy('section_key');

        return view('admin.cms-public-pages.edit', [
            'page' => $cmsPublicPage,
            'fieldsBySection' => $fieldsBySection,
        ]);
    }

    public function update(UpdateCmsPublicPageRequest $request, CmsOriginalPage $cmsPublicPage, CmsContentService $cms)
    {
        if (!$cmsPublicPage->is_editable) {
            return redirect()
                ->route('admin.cms-original-pages.index')
                ->with('error', 'Página não editável.');
        }

        $cms->updateFields($cmsPublicPage, $request->input('fields', []));

        return redirect()
            ->route('admin.cms-original-pages.edit', $cmsPublicPage)
            ->with('success', 'Conteúdo da página atualizado com sucesso!');
    }

    public function editSeo(CmsOriginalPage $cmsPublicPage)
    {
        if (!$cmsPublicPage->seo_enabled) {
            return redirect()
                ->route('admin.cms-original-pages.index')
                ->with('error', 'SEO desabilitado para esta página.');
        }

        $cmsPublicPage->load('seo');

        return view('admin.cms-public-pages.seo', ['page' => $cmsPublicPage]);
    }

    public function updateSeo(UpdateCmsPublicPageSeoRequest $request, CmsOriginalPage $cmsPublicPage, CmsContentService $cms)
    {
        $cms->updateSeo($cmsPublicPage, $request->validated());
        $cms->clearPageCache($cmsPublicPage);

        return redirect()
            ->route('admin.cms-original-pages.seo', $cmsPublicPage)
            ->with('success', 'SEO atualizado com sucesso!');
    }

    public function clearCache(CmsOriginalPage $cmsPublicPage, CmsContentService $cms)
    {
        $cms->clearPageCache($cmsPublicPage);

        return redirect()
            ->route('admin.cms-original-pages.index')
            ->with('success', "Cache da página \"{$cmsPublicPage->admin_label}\" limpo.");
    }
}
