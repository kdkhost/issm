<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryAnalyticsEvent;
use App\Models\GalleryPhoto;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $coverPhotoSubquery = GalleryPhoto::query()
            ->select('image')
            ->whereColumn('gallery_album_id', 'gallery_albums.id')
            ->where('active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(1);

        $albums = GalleryAlbum::withCount(['photos', 'activePhotos', 'projects'])
            ->addSelect(['cover_photo_image' => $coverPhotoSubquery])
            ->with([
                'projects:id,title',
            ])
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->paginate(12);

        $stats = [
            'albums' => GalleryAlbum::count(),
            'active_albums' => GalleryAlbum::where('active', true)->count(),
            'photos' => GalleryPhoto::count(),
            'active_photos' => GalleryPhoto::where('active', true)->count(),
        ];

        $analyticsStart = now()->subDays(30);
        $eventCounts = GalleryAnalyticsEvent::where('occurred_at', '>=', $analyticsStart)
            ->select('event_type', DB::raw('COUNT(*) as total'))
            ->groupBy('event_type')
            ->pluck('total', 'event_type');

        $topAlbums = GalleryAlbum::query()
            ->select('gallery_albums.*')
            ->selectSub(function ($query) use ($analyticsStart) {
                $query->from('gallery_analytics_events')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('gallery_analytics_events.gallery_album_id', 'gallery_albums.id')
                    ->where('occurred_at', '>=', $analyticsStart);
            }, 'analytics_events_count')
            ->having('analytics_events_count', '>', 0)
            ->orderByDesc('analytics_events_count')
            ->limit(5)
            ->get();

        $topPhotos = GalleryPhoto::query()
            ->with('album:id,title')
            ->select('gallery_photos.*')
            ->selectSub(function ($query) use ($analyticsStart) {
                $query->from('gallery_analytics_events')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('gallery_analytics_events.gallery_photo_id', 'gallery_photos.id')
                    ->where('occurred_at', '>=', $analyticsStart);
            }, 'analytics_events_count')
            ->selectSub(function ($query) use ($analyticsStart) {
                $query->from('gallery_analytics_events')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('gallery_analytics_events.gallery_photo_id', 'gallery_photos.id')
                    ->where('event_type', 'photo_download')
                    ->where('occurred_at', '>=', $analyticsStart);
            }, 'downloads_count')
            ->having('analytics_events_count', '>', 0)
            ->orderByDesc('analytics_events_count')
            ->limit(5)
            ->get();

        $recentDownloads = GalleryAnalyticsEvent::with(['album:id,title', 'photo:id,title', 'user:id,name,email'])
            ->where('event_type', 'photo_download')
            ->latest('occurred_at')
            ->limit(8)
            ->get();

        $analytics = [
            'period_label' => 'Últimos 30 dias',
            'events' => [
                'gallery_index' => (int) ($eventCounts['gallery_index'] ?? 0),
                'album_view' => (int) ($eventCounts['album_view'] ?? 0),
                'album_click' => (int) ($eventCounts['album_click'] ?? 0),
                'photo_view' => (int) ($eventCounts['photo_view'] ?? 0),
                'photo_click' => (int) ($eventCounts['photo_click'] ?? 0),
                'photo_share' => (int) ($eventCounts['photo_share'] ?? 0),
                'download_click' => (int) ($eventCounts['download_click'] ?? 0),
                'photo_download' => (int) ($eventCounts['photo_download'] ?? 0),
            ],
            'top_albums' => $topAlbums,
            'top_photos' => $topPhotos,
            'recent_downloads' => $recentDownloads,
        ];

        return view('admin.galeria.index', compact('albums', 'stats', 'analytics'));
    }

    public function create()
    {
        $projects = Project::active()->get();
        $uploadLimitMb = Setting::uploadLimitMb('image');

        return view('admin.galeria.create', compact('projects', 'uploadLimitMb'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->albumRules() + [
            'cover_image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'images' => 'nullable|array',
            'images.*' => 'image|max:' . Setting::uploadLimitKb('image'),
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('gallery/albums', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $album = GalleryAlbum::create($this->albumPayload($validated));
        $album->projects()->sync($validated['project_ids'] ?? []);

        $skippedDuplicates = 0;

        foreach ($request->file('images', []) as $index => $file) {
            $photo = $this->createPhotoFromUpload($album, $file, [
                'title' => $this->titleFromFile($file),
                'sort_order' => $index,
                'active' => true,
            ], false);

            if (! $photo) {
                $skippedDuplicates++;
            }
        }

        $message = 'Album criado com sucesso. Agora voce pode gerenciar as fotos.';
        if ($skippedDuplicates > 0) {
            $message .= " {$skippedDuplicates} foto(s) duplicada(s) foram ignoradas.";
        }

        return redirect()
            ->route('admin.galeria.edit', $album)
            ->with('success', $message);
    }

    public function edit(GalleryAlbum $gallery)
    {
        $gallery->load([
            'projects',
        ]);
        $gallery->loadCount(['photos', 'activePhotos']);

        $projects = Project::active()->get();
        $uploadLimitMb = Setting::uploadLimitMb('image');
        $photos = $gallery->photos()
            ->paginate(12, ['*'], 'fotos')
            ->withQueryString();

        return view('admin.galeria.edit', [
            'album' => $gallery,
            'photos' => $photos,
            'projects' => $projects,
            'uploadLimitMb' => $uploadLimitMb,
        ]);
    }

    public function update(Request $request, GalleryAlbum $gallery)
    {
        $validated = $request->validate($this->albumRules() + [
            'cover_image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
        ]);

        if ($request->hasFile('cover_image')) {
            $this->deletePublicFile($gallery->cover_image);
            $validated['cover_image'] = $request->file('cover_image')->store('gallery/albums', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $gallery->update($this->albumPayload($validated));
        $gallery->projects()->sync($validated['project_ids'] ?? []);

        return redirect()
            ->route('admin.galeria.edit', $gallery)
            ->with('success', 'Album atualizado com sucesso!');
    }

    public function destroy(GalleryAlbum $gallery)
    {
        $gallery->load('photos');

        $this->deletePublicFile($gallery->cover_image);
        foreach ($gallery->photos as $photo) {
            $this->deletePublicFile($photo->image);
        }

        $gallery->delete();

        return redirect()
            ->route('admin.galeria.index')
            ->with('success', 'Album excluido com sucesso!');
    }

    public function show(GalleryAlbum $gallery)
    {
        return redirect()->route('admin.galeria.edit', $gallery);
    }

    public function toggleAlbum(Request $request, GalleryAlbum $gallery)
    {
        $gallery->update(['active' => ! $gallery->active]);

        return $this->jsonOrBack($request, [
            'success' => true,
            'active' => $gallery->active,
            'message' => $gallery->active ? 'Album ativado.' : 'Album desativado.',
        ]);
    }

    public function storePhotos(Request $request, GalleryAlbum $gallery)
    {
        $validated = $request->validate([
            'image' => 'required_without:images|nullable|image|max:' . Setting::uploadLimitKb('image'),
            'images' => 'required_without:image|nullable|array',
            'images.*' => 'image|max:' . Setting::uploadLimitKb('image'),
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $files = $request->file('images') ?: array_filter([$request->file('image')]);
        $photos = [];
        $baseOrder = (int) ($validated['sort_order'] ?? ($gallery->photos()->max('sort_order') + 1));

        foreach ($files as $index => $file) {
            $photo = $this->createPhotoFromUpload($gallery, $file, [
                'title' => $validated['title'] ?? $this->titleFromFile($file),
                'description' => $validated['description'] ?? null,
                'sort_order' => $baseOrder + $index,
                'active' => $request->has('active') ? $request->boolean('active') : true,
            ]);

            if ($photo) {
                $photos[] = $photo;
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($photos) === 1 ? 'Foto enviada com sucesso.' : 'Fotos enviadas com sucesso.',
            'photos' => collect($photos)->map(fn (GalleryPhoto $photo) => $this->photoJson($photo))->values(),
        ]);
    }

    public function updatePhoto(Request $request, GalleryAlbum $gallery, GalleryPhoto $photo)
    {
        $this->abortIfPhotoDoesNotBelongToAlbum($gallery, $photo);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'sort_order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $imageHash = $this->hashUploadedFile($request->file('image'));
            $duplicate = $this->duplicatePhotoForHash($gallery, $imageHash, $photo->id);

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'image' => "Esta imagem ja existe neste album como \"{$duplicate->title}\".",
                ]);
            }

            $this->deletePublicFile($photo->image);
            $validated = array_merge($validated, $this->imagePayload($request->file('image'), $gallery));
        }

        $validated['active'] = $request->boolean('active');
        $photo->update($validated);

        return $this->jsonOrBack($request, [
            'success' => true,
            'message' => 'Foto atualizada com sucesso.',
            'photo' => $this->photoJson($photo->fresh()),
        ], url()->previous());
    }

    public function destroyPhoto(Request $request, GalleryAlbum $gallery, GalleryPhoto $photo)
    {
        $this->abortIfPhotoDoesNotBelongToAlbum($gallery, $photo);

        $this->deletePublicFile($photo->image);
        $photo->delete();

        return $this->jsonOrBack($request, [
            'success' => true,
            'message' => 'Foto excluida com sucesso.',
        ], url()->previous());
    }

    public function togglePhoto(Request $request, GalleryAlbum $gallery, GalleryPhoto $photo)
    {
        $this->abortIfPhotoDoesNotBelongToAlbum($gallery, $photo);

        $photo->update(['active' => ! $photo->active]);

        return response()->json([
            'success' => true,
            'active' => $photo->active,
            'message' => $photo->active ? 'Foto ativada.' : 'Foto desativada.',
        ]);
    }

    private function albumRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_location' => 'nullable|string|max:255',
            'ideal_image_width' => 'required|integer|min:320|max:8000',
            'ideal_image_height' => 'required|integer|min:240|max:8000',
            'sort_order' => 'nullable|integer',
            'active' => 'nullable|boolean',
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'integer|exists:projects,id',
        ];
    }

    private function albumPayload(array $validated): array
    {
        return collect($validated)
            ->only([
                'title',
                'description',
                'event_date',
                'event_location',
                'cover_image',
                'ideal_image_width',
                'ideal_image_height',
                'sort_order',
                'active',
            ])
            ->toArray();
    }

    private function createPhotoFromUpload(GalleryAlbum $album, UploadedFile $file, array $overrides = [], bool $failOnDuplicate = true): ?GalleryPhoto
    {
        $imageHash = $this->hashUploadedFile($file);
        $duplicate = $this->duplicatePhotoForHash($album, $imageHash);

        if ($duplicate) {
            if ($failOnDuplicate) {
                throw ValidationException::withMessages([
                    'image' => "Esta imagem ja existe neste album como \"{$duplicate->title}\".",
                ]);
            }

            return null;
        }

        return $album->photos()->create(array_merge([
            'title' => $this->titleFromFile($file),
            'description' => null,
            'sort_order' => 0,
            'active' => true,
        ], $overrides, $this->imagePayload($file, $album)));
    }

    private function imagePayload(UploadedFile $file, GalleryAlbum $album): array
    {
        [$width, $height] = @getimagesize($file->getPathname()) ?: [null, null];

        return [
            'image' => $file->store('gallery/' . $album->id, 'public'),
            'image_hash' => $this->hashUploadedFile($file),
            'width' => $width,
            'height' => $height,
            'size_kb' => (int) ceil($file->getSize() / 1024),
        ];
    }

    private function hashUploadedFile(UploadedFile $file): string
    {
        return hash_file('sha256', $file->getPathname());
    }

    private function duplicatePhotoForHash(GalleryAlbum $album, string $imageHash, ?int $ignorePhotoId = null): ?GalleryPhoto
    {
        return $album->photos()
            ->where('image_hash', $imageHash)
            ->when($ignorePhotoId, fn ($query) => $query->whereKeyNot($ignorePhotoId))
            ->first();
    }

    private function titleFromFile(UploadedFile $file): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = trim(str_replace(['-', '_'], ' ', $name));

        return Str::title($name ?: 'Foto do album');
    }

    private function photoJson(GalleryPhoto $photo): array
    {
        return [
            'id' => $photo->id,
            'title' => $photo->title,
            'description' => $photo->description,
            'image' => $photo->image,
            'image_url' => asset('media/' . $photo->image),
            'width' => $photo->width,
            'height' => $photo->height,
            'size_kb' => $photo->size_kb,
            'sort_order' => $photo->sort_order,
            'active' => $photo->active,
        ];
    }

    private function abortIfPhotoDoesNotBelongToAlbum(GalleryAlbum $album, GalleryPhoto $photo): void
    {
        abort_unless((int) $photo->gallery_album_id === (int) $album->id, 404);
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function jsonOrBack(Request $request, array $payload, ?string $redirectTo = null)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($payload);
        }

        return redirect($redirectTo ?: url()->previous())
            ->with($payload['success'] ? 'success' : 'error', $payload['message'] ?? 'Operacao realizada.');
    }
}
