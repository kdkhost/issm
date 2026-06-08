<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCmsPublicPageRequest;
use App\Http\Requests\Admin\UpdateCmsPublicPageSeoRequest;
use App\Models\CmsPublicPage;
use App\Services\Cms\CmsContentService;
use Illuminate\Http\Request;

class CmsPublicPageController extends Controller
{
    public function index()
    {
        $pages = CmsPublicPage::withCount('fields')
            ->orderBy('sort_order')
            ->get()
            ->map(function (CmsPublicPage $page) {
                $page->pending_count = $page->pendingFieldsCount();

                return $page;
            });

        return view('admin.cms-public-pages.index', compact('pages'));
    }

    public function edit(CmsPublicPage $cmsPublicPage)
    {
        if (!$cmsPublicPage->is_editable) {
            return redirect()
                ->route('admin.cms-public-pages.index')
                ->with('error', 'Esta página é um modelo dinâmico e não pode ser editada por aqui.');
        }

        $cmsPublicPage->load(['sections', 'fields']);

        $fieldsBySection = $cmsPublicPage->fields->groupBy('section_key');

        return view('admin.cms-public-pages.edit', [
            'page' => $cmsPublicPage,
            'fieldsBySection' => $fieldsBySection,
        ]);
    }

    public function update(UpdateCmsPublicPageRequest $request, CmsPublicPage $cmsPublicPage, CmsContentService $cms)
    {
        if (!$cmsPublicPage->is_editable) {
            return redirect()
                ->route('admin.cms-public-pages.index')
                ->with('error', 'Página não editável.');
        }

        $cms->updateFields($cmsPublicPage, $request->input('fields', []));

        return redirect()
            ->route('admin.cms-public-pages.edit', $cmsPublicPage)
            ->with('success', 'Conteúdo da página atualizado com sucesso!');
    }

    public function editSeo(CmsPublicPage $cmsPublicPage)
    {
        if (!$cmsPublicPage->seo_enabled) {
            return redirect()
                ->route('admin.cms-public-pages.index')
                ->with('error', 'SEO desabilitado para esta página.');
        }

        $cmsPublicPage->load('seo');

        return view('admin.cms-public-pages.seo', ['page' => $cmsPublicPage]);
    }

    public function updateSeo(UpdateCmsPublicPageSeoRequest $request, CmsPublicPage $cmsPublicPage, CmsContentService $cms)
    {
        $cms->updateSeo($cmsPublicPage, $request->validated());
        $cms->clearPageCache($cmsPublicPage);

        return redirect()
            ->route('admin.cms-public-pages.seo', $cmsPublicPage)
            ->with('success', 'SEO atualizado com sucesso!');
    }

    public function editFullHtml(CmsPublicPage $cmsPublicPage)
    {
        if (!$cmsPublicPage->is_editable) {
            return redirect()
                ->route('admin.cms-public-pages.index')
                ->with('error', 'Esta página é um modelo dinâmico e não pode ser editada por aqui.');
        }

        return view('admin.cms-public-pages.edit-full-html', ['page' => $cmsPublicPage]);
    }

    public function updateFullHtml(Request $request, CmsPublicPage $cmsPublicPage, CmsContentService $cms)
    {
        if (!$cmsPublicPage->is_editable) {
            return redirect()
                ->route('admin.cms-public-pages.index')
                ->with('error', 'Página não editável.');
        }

        $validated = $request->validate([
            'custom_html' => 'nullable|string',
            'use_custom_html' => 'boolean',
        ]);

        $cmsPublicPage->update([
            'custom_html' => $validated['custom_html'] ?? null,
            'use_custom_html' => $validated['use_custom_html'] ?? false,
        ]);

        $cms->clearPageCache($cmsPublicPage);

        return redirect()
            ->route('admin.cms-public-pages.edit-full-html', $cmsPublicPage)
            ->with('success', 'HTML completo atualizado com sucesso!');
    }

    public function clearCache(CmsPublicPage $cmsPublicPage, CmsContentService $cms)
    {
        $cms->clearPageCache($cmsPublicPage);

        return redirect()
            ->route('admin.cms-public-pages.index')
            ->with('success', "Cache da página \"{$cmsPublicPage->admin_label}\" limpo.");
    }
}
