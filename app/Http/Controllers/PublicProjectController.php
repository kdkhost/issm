<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;

class PublicProjectController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::active()->featured()->take(3)->get();
        $projects = Project::active()->paginate(9);
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        $cmsData = $this->loadCmsPage('projetos');

        if ($cmsData['cmsPage']) {
            return view('public.cms.page', array_merge(
                ['page' => $cmsData['cmsPage'], 'sections' => $cmsData['cmsSections']],
                compact('featuredProjects', 'projects', 'settings'), $cmsData
            ));
        }

        return view('projects.index', array_merge(compact('projects', 'settings'), $cmsData));
    }

    public function show(string $slug)
    {
        $project = Project::active()->where('slug', $slug)->firstOrFail();
        $related = Project::active()->where('id', '!=', $project->id)->take(3)->get();
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        $cmsData = $this->loadCmsPage('projetos');
        return view('projects.show', array_merge(compact('project', 'related', 'settings'), $cmsData));
    }
}
