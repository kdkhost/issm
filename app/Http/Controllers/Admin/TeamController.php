<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('order')->paginate(15);
        return view('admin.equipe.index', compact('members'));
    }

    public function create()
    {
        return view('admin.equipe.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'email' => 'nullable|email',
            'linkedin' => 'nullable|url',
            'whatsapp' => 'nullable|string|max:20',
            'phone_support' => 'nullable|string|max:30',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
            'support_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $validated['support_active'] = $request->boolean('support_active');
        TeamMember::create($validated);

        return redirect()->route('admin.equipe.index')->with('success', 'Membro criado com sucesso!');
    }

    public function edit(TeamMember $team)
    {
        return view('admin.equipe.edit', ['member' => $team]);
    }

    public function update(Request $request, TeamMember $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'email' => 'nullable|email',
            'linkedin' => 'nullable|url',
            'whatsapp' => 'nullable|string|max:20',
            'phone_support' => 'nullable|string|max:30',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
            'support_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $validated['support_active'] = $request->boolean('support_active');
        $team->update($validated);

        return redirect()->route('admin.equipe.index')->with('success', 'Membro atualizado com sucesso!');
    }

    public function destroy(TeamMember $team)
    {
        $team->delete();
        return redirect()->route('admin.equipe.index')->with('success', 'Membro excluido com sucesso!');
    }
}
