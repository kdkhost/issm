<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransparencyDocument;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransparencyController extends Controller
{
    public function index()
    {
        $documents = TransparencyDocument::orderBy('year', 'desc')->orderBy('published_at', 'desc')->paginate(15);
        return view('admin.transparency.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.transparency.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:' . Setting::uploadLimitKb('document', 10240),
            'category' => 'required|string',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'published_at' => 'required|date',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('transparency', 'public');
        }

        $validated['active'] = $request->boolean('active', true);
        TransparencyDocument::create($validated);

        return redirect()->route('admin.transparencia.index')->with('success', 'Documento adicionado com sucesso!');
    }

    public function edit(TransparencyDocument $transparency)
    {
        return view('admin.transparency.edit', compact('transparency'));
    }

    public function update(Request $request, TransparencyDocument $transparency)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:' . Setting::uploadLimitKb('document', 10240),
            'category' => 'required|string',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'published_at' => 'required|date',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if ($transparency->file_path) {
                Storage::disk('public')->delete($transparency->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('transparency', 'public');
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
