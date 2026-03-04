<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $items = Gallery::orderBy('order')->paginate(20);
        return view('admin.galeria.index', compact('items'));
    }

    public function create()
    {
        return view('admin.galeria.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:5120',
            'album' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $validated['image'] = $request->file('image')->store('gallery', 'public');
        $validated['active'] = $request->boolean('active');
        Gallery::create($validated);

        return redirect()->route('admin.galeria.index')->with('success', 'Imagem adicionada com sucesso!');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galeria.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'album' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $gallery->update($validated);

        return redirect()->route('admin.galeria.index')->with('success', 'Imagem atualizada com sucesso!');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return redirect()->route('admin.galeria.index')->with('success', 'Imagem excluida com sucesso!');
    }

    public function show(Gallery $gallery) { return $this->edit($gallery); }
}
