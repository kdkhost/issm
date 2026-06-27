<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\Setting;

class PublicGalleryController extends Controller
{
    public function index()
    {
        $filter = request('album') ?: request('filter');
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];

        $allAlbums = GalleryAlbum::active()
            ->whereHas('activePhotos')
            ->with([
                'activePhotos',
                'projects' => fn ($query) => $query
                    ->where('projects.active', true)
                    ->orderBy('projects.order')
                    ->orderBy('projects.title'),
            ])
            ->get();

        $selectedAlbum = null;
        if ($filter && ! in_array($filter, ['Todos', 'Galeria', 'Projetos'], true)) {
            $selectedAlbum = $allAlbums->firstWhere('slug', $filter)
                ?: $allAlbums->firstWhere('title', $filter);
        }

        $albums = $selectedAlbum ? collect([$selectedAlbum]) : $allAlbums;

        $allItems = $albums->flatMap(function (GalleryAlbum $album) {
            return $album->activePhotos->map(function ($photo) use ($album) {
                return (object) [
                    'id' => 'g-' . $photo->id,
                    'title' => $photo->title,
                    'image' => $photo->image,
                    'album' => $album->title,
                    'album_slug' => $album->slug,
                    'event_date' => $album->event_date,
                    'event_location' => $album->event_location,
                    'type' => 'gallery',
                    'source' => 'Galeria',
                    'link' => null,
                ];
            });
        })->values();

        $totalAlbums = $allAlbums->count();
        $totalGallery = $allAlbums->sum(fn (GalleryAlbum $album) => $album->activePhotos->count());
        $totalProjects = $allAlbums
            ->flatMap(fn (GalleryAlbum $album) => $album->projects->pluck('id'))
            ->unique()
            ->count();

        return view('gallery.index', compact(
            'albums',
            'allAlbums',
            'allItems',
            'filter',
            'selectedAlbum',
            'settings',
            'totalAlbums',
            'totalGallery',
            'totalProjects'
        ));
    }
}
