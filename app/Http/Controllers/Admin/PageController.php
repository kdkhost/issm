<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
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
            'image' => 'nullable|image|max:2048',
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
        Page::create($validated);

        return redirect()->route('admin.paginas.index')->with('success', 'Pagina criada com sucesso!');
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
            'image' => 'nullable|image|max:2048',
            'active' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('pages', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $validated['show_in_menu'] = $request->boolean('show_in_menu');
        $page->update($validated);

        return redirect()->route('admin.paginas.index')->with('success', 'Pagina atualizada com sucesso!');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.paginas.index')->with('success', 'Pagina excluida com sucesso!');
    }

    public function show(Page $page) { return $this->edit($page); }
}
