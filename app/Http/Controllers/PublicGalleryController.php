<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\Project;
use App\Models\Setting;

class PublicGalleryController extends Controller
{
    public function index()
    {
        $filter = request('album') ?: request('filter');
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        $albumPageSize = 9;
        $photoPageSize = 24;
        $coverPhotoSubquery = fn () => GalleryPhoto::query()
            ->select('image')
            ->whereColumn('gallery_album_id', 'gallery_albums.id')
            ->where('active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(1);

        $allAlbums = GalleryAlbum::active()
            ->whereHas('activePhotos')
            ->withCount('activePhotos')
            ->get();

        $selectedAlbum = null;
        if ($filter && ! in_array($filter, ['Todos', 'Galeria', 'Projetos'], true)) {
            $selectedAlbum = $allAlbums->firstWhere('slug', $filter)
                ?: $allAlbums->firstWhere('title', $filter);
        }

        $photos = null;

        if ($selectedAlbum) {
            $selectedAlbum = GalleryAlbum::active()
                ->whereKey($selectedAlbum->id)
                ->whereHas('activePhotos')
                ->addSelect(['cover_photo_image' => $coverPhotoSubquery()])
                ->withCount('activePhotos')
                ->with([
                    'projects' => fn ($query) => $query
                        ->where('projects.active', true)
                        ->orderBy('projects.order')
                        ->orderBy('projects.title')
                        ->select('projects.id', 'projects.title', 'projects.slug'),
                ])
                ->first();

            $albums = collect($selectedAlbum ? [$selectedAlbum] : []);
            $photos = $selectedAlbum
                ? $selectedAlbum->activePhotos()->paginate($photoPageSize, ['*'], 'fotos')->withQueryString()
                : null;
        } else {
            $albums = GalleryAlbum::active()
                ->whereHas('activePhotos')
                ->addSelect(['cover_photo_image' => $coverPhotoSubquery()])
                ->withCount('activePhotos')
                ->with([
                    'projects' => fn ($query) => $query
                        ->where('projects.active', true)
                        ->orderBy('projects.order')
                        ->orderBy('projects.title')
                        ->select('projects.id', 'projects.title', 'projects.slug'),
                ])
                ->paginate($albumPageSize, ['*'], 'albuns')
                ->withQueryString();
        }

        $totalAlbums = $allAlbums->count();
        $totalGallery = $allAlbums->sum('active_photos_count');
        $totalProjects = Project::where('active', true)
            ->whereHas('galleryAlbums', function ($query) {
                $query->where('gallery_albums.active', true)
                    ->whereHas('activePhotos');
            })
            ->count();
        $allItems = collect();

        return view('gallery.index', compact(
            'albums',
            'allAlbums',
            'allItems',
            'photos',
            'filter',
            'selectedAlbum',
            'settings',
            'totalAlbums',
            'totalGallery',
            'totalProjects'
        ));
    }
}
