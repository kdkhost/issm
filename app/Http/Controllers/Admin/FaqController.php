<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('order')->paginate(15);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer',
            'active'   => 'nullable|boolean',
        ]);

        $validated['active'] = $request->boolean('active');
        Faq::create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'Pergunta criada com sucesso!');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer',
            'active'   => 'nullable|boolean',
        ]);

        $validated['active'] = $request->boolean('active');
        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'Pergunta atualizada com sucesso!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'Pergunta excluída com sucesso!');
    }

    public function toggleActive(Faq $faq)
    {
        $faq->update(['active' => !$faq->active]);
        return response()->json(['active' => $faq->active]);
    }
}
