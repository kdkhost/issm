<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Project;
use App\Models\Setting;

class PublicGalleryController extends Controller
{
    public function index()
    {
        $filter = request('filter'); // 'galeria', 'projetos', or album name
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];

        // ── Gallery items ──
        $galleryItems = Gallery::active()->get()->map(function ($item) {
            return (object) [
                'id'       => 'g-'.$item->id,
                'title'    => $item->title,
                'image'    => $item->image,
                'album'    => $item->album ?: 'Galeria',
                'type'     => 'gallery',
                'source'   => 'Galeria',
                'link'     => null,
            ];
        });

        // ── Project images ──
        $projectItems = Project::active()
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->get()
            ->map(function ($project) {
                return (object) [
                    'id'       => 'p-'.$project->id,
                    'title'    => $project->title,
                    'image'    => $project->image,
                    'album'    => 'Projeto: '.$project->title,
                    'type'     => 'project',
                    'source'   => 'Projetos',
                    'link'     => route('projects.show', $project->slug),
                ];
            });

        // ── Merge ──
        $allItems = $galleryItems->concat($projectItems);

        // ── Albums / filters ──
        $galleryAlbums = Gallery::active()->whereNotNull('album')->where('album', '!=', '')->distinct()->pluck('album')->toArray();
        $filterOptions = array_merge(['Todos', 'Galeria', 'Projetos'], $galleryAlbums);

        // ── Apply filter ──
        if ($filter === 'galeria' || $filter === 'Galeria') {
            $allItems = $allItems->where('type', 'gallery');
        } elseif ($filter === 'projetos' || $filter === 'Projetos') {
            $allItems = $allItems->where('type', 'project');
        } elseif ($filter && $filter !== 'Todos') {
            $allItems = $allItems->filter(fn($i) => $i->album === $filter);
        }

        $totalGallery  = $galleryItems->count();
        $totalProjects = $projectItems->count();

        return view('gallery.index', compact(
            'allItems', 'filter', 'filterOptions', 'settings',
            'totalGallery', 'totalProjects'
        ));
    }
}
