<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

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

    public function watermarked(Request $request, GalleryPhoto $photo)
    {
        $photo->load('album');

        abort_unless($photo->active && $photo->album && $photo->album->active, 404);

        $sourcePath = $this->resolveMediaPath($photo->image);
        abort_unless($sourcePath, 404);

        $logoPath = $this->siteLogoPath();
        $fileName = (Str::slug($photo->title ?: pathinfo($photo->image, PATHINFO_FILENAME)) ?: 'foto-galeria').'.'.$this->extensionFor($sourcePath);
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        if (! $logoPath) {
            return response()->file($sourcePath, [
                'Content-Disposition' => "{$disposition}; filename=\"{$fileName}\"",
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        $cachedPath = $this->cachedWatermarkedPath($photo, $sourcePath, $logoPath);
        $absoluteCachedPath = Storage::disk('public')->path($cachedPath);

        if (! is_file($absoluteCachedPath)) {
            Storage::disk('public')->makeDirectory(dirname($cachedPath));
            $this->createWatermarkedImage($sourcePath, $logoPath, $absoluteCachedPath);
        }

        return response()->file($absoluteCachedPath, [
            'Content-Disposition' => "{$disposition}; filename=\"{$fileName}\"",
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    private function resolveMediaPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $normalized = ltrim(Str::after($path, 'media/'), '/\\');
        $absolutePath = Storage::disk('public')->path($normalized);

        return is_file($absolutePath) ? $absolutePath : null;
    }

    private function siteLogoPath(): ?string
    {
        foreach (['site_logo', 'logo', 'site_favicon', 'favicon'] as $key) {
            $path = $this->resolveMediaPath(Setting::get($key));

            if ($path) {
                return $path;
            }
        }

        return null;
    }

    private function cachedWatermarkedPath(GalleryPhoto $photo, string $sourcePath, string $logoPath): string
    {
        $signature = md5(implode('|', [
            $photo->id,
            $photo->updated_at?->timestamp,
            $photo->image,
            filemtime($sourcePath) ?: 0,
            filemtime($logoPath) ?: 0,
        ]));

        return 'gallery/watermarked/'.$photo->id.'-'.$signature.'.'.$this->extensionFor($sourcePath);
    }

    private function createWatermarkedImage(string $sourcePath, string $logoPath, string $targetPath): void
    {
        $image = Image::make($sourcePath)->orientate();
        $watermark = Image::make($logoPath)->orientate();
        $shortSide = max(1, min($image->width(), $image->height()));
        $watermarkWidth = (int) max(42, min(120, round($shortSide * 0.12)));
        $margin = (int) max(12, round($shortSide * 0.035));

        $watermark->resize($watermarkWidth, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $watermark->opacity(15);
        $image->insert($watermark, 'bottom-right', $margin, $margin);
        $image->save($targetPath, 90, $this->extensionFor($sourcePath));
    }

    private function extensionFor(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true) ? $extension : 'jpg';
    }
}
