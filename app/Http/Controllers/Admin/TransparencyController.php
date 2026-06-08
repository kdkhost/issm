<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransparencyCategory;
use App\Models\TransparencyDocument;
use App\Models\Setting;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransparencyController extends Controller
{
    public function index()
    {
        $documents = TransparencyDocument::with('categoryModel')->orderBy('year', 'desc')->orderBy('published_at', 'desc')->paginate(15);
        return view('admin.transparency.index', compact('documents'));
    }

    public function create()
    {
        $categories = TransparencyCategory::active()->ordered()->get();
        return view('admin.transparency.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:' . Setting::uploadLimitKb('document', 10240),
            'file_path' => 'nullable|string|max:500',
            'category_id' => 'required|exists:transparency_categories,id',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'published_at' => 'required|date',
            'active' => 'nullable|boolean',
        ]);

        $category = TransparencyCategory::findOrFail($validated['category_id']);
        $validated['category'] = $category->name;

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('transparency', 'public');
        } elseif (!empty($validated['file_path'])) {
            $validated['file_path'] = str_replace(asset('storage/'), '', $validated['file_path']);
        }

        $validated['active'] = $request->boolean('active', true);
        TransparencyDocument::create($validated);

        return redirect()->route('admin.transparencia.index')->with('success', 'Documento adicionado com sucesso!');
    }

    public function edit(TransparencyDocument $transparency)
    {
        $categories = TransparencyCategory::active()->ordered()->get();
        return view('admin.transparency.edit', compact('transparency', 'categories'));
    }

    public function update(Request $request, TransparencyDocument $transparency)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:' . Setting::uploadLimitKb('document', 10240),
            'file_path' => 'nullable|string|max:500',
            'category_id' => 'required|exists:transparency_categories,id',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'published_at' => 'required|date',
            'active' => 'nullable|boolean',
        ]);

        $category = TransparencyCategory::findOrFail($validated['category_id']);
        $validated['category'] = $category->name;

        if ($request->hasFile('file')) {
            if ($transparency->file_path) {
                Storage::disk('public')->delete($transparency->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('transparency', 'public');
        } elseif (!empty($validated['file_path']) && $validated['file_path'] !== $transparency->file_path) {
            if ($transparency->file_path) {
                Storage::disk('public')->delete($transparency->file_path);
            }
            $validated['file_path'] = str_replace(asset('storage/'), '', $validated['file_path']);
        }

        $validated['active'] = $request->boolean('active', true);
        $transparency->update($validated);

        return redirect()->route('admin.transparencia.index')->with('success', 'Documento atualizado com sucesso!');
    }

    public function destroy(TransparencyDocument $transparency)
    {
        if ($transparency->file_path) {
            Storage::disk('public')->delete($transparency->file_path);
        }
        $transparency->delete();
        return redirect()->route('admin.transparencia.index')->with('success', 'Documento excluido com sucesso!');
    }
}
