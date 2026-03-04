<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ods;
use Illuminate\Http\Request;

class OdsController extends Controller
{
    public function index()
    {
        $odsList = Ods::orderBy('number')->paginate(20);
        return view('admin.ods.index', compact('odsList'));
    }

    public function edit(Ods $od)
    {
        return view('admin.ods.edit', compact('od'));
    }

    public function update(Request $request, Ods $od)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:20',
            'active' => 'nullable|boolean',
        ]);

        $validated['active'] = $request->boolean('active');
        $od->update($validated);

        return redirect()->route('admin.ods.index')->with('success', 'ODS atualizado com sucesso!');
    }

    public function create() { return redirect()->route('admin.ods.index'); }
    public function store(Request $request) { return redirect()->route('admin.ods.index'); }
    public function show(Ods $od) { return $this->edit($od); }
    public function destroy(Ods $od) { return redirect()->route('admin.ods.index'); }
}
