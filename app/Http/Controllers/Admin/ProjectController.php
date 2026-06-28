<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ods;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount([
            'supportRequests',
            'supportRequests as new_support_requests_count' => fn ($query) => $query->where('status', 'new'),
        ])->latest()->paginate(15);

        return view('admin.projetos.index', compact('projects'));
    }

    public function create()
    {
        $odsList = Ods::active()->get();
        return view('admin.projetos.create', compact('odsList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'category' => 'nullable|string|max:100',
            'ods_goals' => 'nullable|array',
            'status' => 'nullable|string|in:active,completed,planned',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'featured' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('projects/og', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['featured'] = $request->boolean('featured');
        $validated['active'] = $request->boolean('active');

        Project::create($validated);

        return redirect()->route('admin.projetos.index')->with('success', 'Projeto criado com sucesso!');
    }

    public function edit(Project $project)
    {
        $odsList = Ods::active()->get();
        $project->loadCount([
            'supportRequests',
            'supportRequests as new_support_requests_count' => fn ($query) => $query->where('status', 'new'),
        ]);
        $recentSupportRequests = $project->supportRequests()
            ->with('supportType:id,name,category')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.projetos.edit', compact('project', 'odsList', 'recentSupportRequests'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'category' => 'nullable|string|max:100',
            'ods_goals' => 'nullable|array',
            'status' => 'nullable|string|in:active,completed,planned',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'featured' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('projects/og', 'public');
        }

        $validated['featured'] = $request->boolean('featured');
        $validated['active'] = $request->boolean('active');

        $project->update($validated);

        return redirect()->route('admin.projetos.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projetos.index')->with('success', 'Projeto excluído com sucesso!');
    }
}
