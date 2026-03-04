<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('order')->paginate(15);
        return view('admin.parceiros.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.parceiros.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:10240',
            'url' => 'nullable|url',
            'type' => 'nullable|string|in:partner,sponsor,supporter',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $validated['active'] = $request->boolean('active');
        Partner::create($validated);

        return redirect()->route('admin.parceiros.index')->with('success', 'Parceiro criado com sucesso!');
    }

    public function edit(Partner $partner)
    {
        return view('admin.parceiros.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:10240',
            'url' => 'nullable|url',
            'type' => 'nullable|string|in:partner,sponsor,supporter',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $partner->update($validated);

        return redirect()->route('admin.parceiros.index')->with('success', 'Parceiro atualizado com sucesso!');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('admin.parceiros.index')->with('success', 'Parceiro excluido com sucesso!');
    }

    public function show(Partner $partner) { return $this->edit($partner); }
}
