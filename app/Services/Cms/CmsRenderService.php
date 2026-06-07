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

use App\Models\CmsBlock;
use App\Models\CmsPage;
use App\Models\CmsSection;
use App\Services\Cms\CmsCacheService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewInstance;

class CmsRenderService
{
    protected CmsCacheService $cacheService;

    protected const BLOCK_VIEW_PATH = 'public.cms.blocks.';
    protected const PAGE_VIEW_PATH = 'public.cms.page';

    public function __construct(CmsCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function renderPage(string $slug): ViewInstance|string
    {
        if ($this->hasCmsContent($slug)) {
            $page = CmsPage::where('slug', $slug)
                ->where('active', true)
                ->where('status', 'published')
                ->firstOrFail();

            $sections = $page->sections()
                ->where('active', true)
                ->orderBy('order')
                ->get();

            return view(static::PAGE_VIEW_PATH, compact('page', 'sections'));
        }

        if ($this->hasFallback($slug)) {
            return $this->renderFallback($slug);
        }

        abort(404, "Página '{$slug}' não encontrada.");
    }

    public function renderBlock(CmsBlock $block): ViewInstance|string
    {
        $viewPath = $this->getBlockView($block->type);

        if (View::exists($viewPath)) {
            return view($viewPath, [
                'block' => $block,
                'content' => $block->content,
                'settings' => $block->settings ?? [],
            ]);
        }

        return view(static::BLOCK_VIEW_PATH . 'default', [
            'block' => $block,
            'content' => $block->content,
        ]);
    }

    public function renderBlocks(int $sectionId): string
    {
        $key = "cms.blocks.section.{$sectionId}";

        return $this->cacheService->remember($key, function () use ($sectionId) {
            $blocks = CmsBlock::where('section_id', $sectionId)
                ->where('active', true)
                ->orderBy('order')
                ->get();

            $output = '';

            foreach ($blocks as $block) {
                $output .= $this->renderBlock($block)->render();
            }

            return $output;
        });
    }

    public function renderSection(int $sectionId): string
    {
        $key = "cms.sections.{$sectionId}";

        return $this->cacheService->remember($key, function () use ($sectionId) {
            $section = CmsSection::with(['blocks' => function ($query) {
                $query->where('active', true)->orderBy('order');
            }])->findOrFail($sectionId);

            $viewPath = 'public.cms.sections.' . ($section->template ?? 'default');

            if (View::exists($viewPath)) {
                return view($viewPath, ['section' => $section])->render();
            }

            $blocksHtml = '';
            foreach ($section->blocks as $block) {
                $blocksHtml .= $this->renderBlock($block)->render();
            }

            return '<div class="cms-section cms-section-' . e($section->id) . '">'
                . $blocksHtml
                . '</div>';
        });
    }

    public function getBlockView(string $type): string
    {
        $map = [
            'text' => 'text',
            'html' => 'html',
            'image' => 'image',
            'gallery' => 'gallery',
            'video' => 'video',
            'hero' => 'hero',
            'cards' => 'cards',
            'accordion' => 'accordion',
            'tabs' => 'tabs',
            'testimonials' => 'testimonials',
            'cta' => 'cta',
            'contact_form' => 'contact_form',
            'map' => 'map',
            'counter' => 'counter',
            'timeline' => 'timeline',
            'pricing' => 'pricing',
            'team' => 'team',
            'faq' => 'faq',
            'logos' => 'logos',
            'divider' => 'divider',
            'code' => 'code',
            'raw_html' => 'raw_html',
        ];

        $view = $map[$type] ?? 'default';

        return static::BLOCK_VIEW_PATH . $view;
    }

    public function hasFallback(string $slug): bool
    {
        $possibleViews = [
            "pages.{$slug}",
            "public.{$slug}",
            "public.pages.{$slug}",
            "site.{$slug}",
        ];

        foreach ($possibleViews as $view) {
            if (View::exists($view)) {
                return true;
            }
        }

        return false;
    }

    public function renderFallback(string $slug): ViewInstance
    {
        $viewMap = [
            "pages.{$slug}" => "pages.{$slug}",
            "public.{$slug}" => "public.{$slug}",
            "public.pages.{$slug}" => "public.pages.{$slug}",
            "site.{$slug}" => "site.{$slug}",
        ];

        foreach ($viewMap as $view) {
            if (View::exists($view)) {
                return view($view);
            }
        }

        abort(404);
    }

    public function hasCmsContent(string $slug): bool
    {
        $key = "cms.page.exists.{$slug}";

        return $this->cacheService->remember($key, function () use ($slug) {
            return CmsPage::where('slug', $slug)
                ->where('active', true)
                ->where('status', 'published')
                ->exists();
        });
    }

    public function extractHardcodedText(string $viewPath): array
    {
        $fullPath = View::getFinder()->find($viewPath);

        if (!File::exists($fullPath)) {
            return [];
        }

        $content = File::get($fullPath);

        $texts = [];

        preg_match_all('/\{\{[\s]*e?\(?\s*\$(\w+)\s*(?:->(\w+))?\s*\)?[\s]*\}\}/', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $texts[] = [
                'type' => 'variable',
                'expression' => $match[0],
                'variable' => $match[1],
                'property' => $match[2] ?? null,
            ];
        }

        preg_match_all('/@lang\([\'"]([^\'"]+)[\'"]\)/', $content, $langMatches);
        foreach ($langMatches[1] as $key) {
            $texts[] = [
                'type' => 'lang',
                'key' => $key,
            ];
        }

        preg_match_all('/>{{([^<]+)}}/', $content, $inlineMatches);
        foreach ($inlineMatches[1] as $text) {
            $trimmed = trim($text);
            if (!empty($trimmed) && !str_contains($trimmed, '$') && !str_contains($trimmed, '@')) {
                $texts[] = [
                    'type' => 'inline_text',
                    'text' => $trimmed,
                ];
            }
        }

        preg_match_all('/<h[1-6][^>]*>([^<]+)<\/h[1-6]>/', $content, $headingMatches);
        foreach ($headingMatches[1] as $heading) {
            $trimmed = trim(strip_tags($heading));
            if (!empty($trimmed) && !str_contains($trimmed, '{{')) {
                $texts[] = [
                    'type' => 'heading',
                    'text' => $trimmed,
                ];
            }
        }

        preg_match_all('/<p[^>]*>([^<]+)<\/p>/', $content, $paragraphMatches);
        foreach ($paragraphMatches[1] as $paragraph) {
            $trimmed = trim(strip_tags($paragraph));
            if (!empty($trimmed) && !str_contains($trimmed, '{{') && strlen($trimmed) > 20) {
                $texts[] = [
                    'type' => 'paragraph',
                    'text' => $trimmed,
                ];
            }
        }

        preg_match_all('/placeholder="([^"]+)"/', $content, $placeholderMatches);
        foreach ($placeholderMatches[1] as $placeholder) {
            $texts[] = [
                'type' => 'placeholder',
                'text' => $placeholder,
            ];
        }

        return $texts;
    }
}
