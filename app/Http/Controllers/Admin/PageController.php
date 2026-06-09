<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('order')->paginate(15);
        return view('admin.paginas.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.paginas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'seo_tags' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'robots_meta' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'active' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('pages', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['active'] = $request->boolean('active');
        $validated['show_in_menu'] = $request->boolean('show_in_menu');
        $validated['seo_score'] = $this->calculateSeoScore($validated);
        Page::create($validated);

        return redirect()->route('admin.paginas.index')->with('success', 'Página criada com sucesso!');
    }

    public function edit(Page $page)
    {
        return view('admin.paginas.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'seo_tags' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'robots_meta' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'active' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('pages', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $validated['show_in_menu'] = $request->boolean('show_in_menu');
        $validated['seo_score'] = $this->calculateSeoScore($validated);
        $page->update($validated);

        return redirect()->route('admin.paginas.index')->with('success', 'Página atualizada com sucesso!');
    }

    private function calculateSeoScore(array $data): int
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

        if (trim($mt)) { $score += 15; }
        if (mb_strlen($mt) >= 50 && mb_strlen($mt) <= 60) { $score += 10; }
        if (trim($md)) { $score += 15; }
        if (mb_strlen($md) >= 120 && mb_strlen($md) <= 160) { $score += 10; }
        if (trim($ot)) { $score += 10; }
        if (trim($od)) { $score += 10; }
        if (trim($oi)) { $score += 15; }
        if (trim($mk)) { $score += 5; }
        if (trim($cu)) { $score += 5; }
        if (trim($rm)) { $score += 5; }
        if (trim($data['seo_tags'] ?? '')) { $score += 5; }

        return min($score, 100);
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.paginas.index')->with('success', 'Página excluida com sucesso!');
    }

    public function show(Page $page) { return $this->edit($page); }
}
