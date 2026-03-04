<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;

class PublicProjectController extends Controller
{
    public function index()
    {
        $projects = Project::active()->paginate(9);
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        return view('projects.index', compact('projects', 'settings'));
    }

    public function show(string $slug)
    {
        $project = Project::active()->where('slug', $slug)->firstOrFail();
        $related = Project::active()->where('id', '!=', $project->id)->take(3)->get();
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        return view('projects.show', compact('project', 'related', 'settings'));
    }
}
