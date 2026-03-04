<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('user')->latest()->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'featured' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        $validated['featured'] = $request->boolean('featured');
        $validated['active'] = $request->boolean('active');

        News::create($validated);

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia criada com sucesso!');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'featured' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $validated['featured'] = $request->boolean('featured');
        $validated['active'] = $request->boolean('active');

        $news->update($validated);

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia atualizada com sucesso!');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.noticias.index')->with('success', 'Noticia excluida com sucesso!');
    }
}
