<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::withCount(['photos', 'activePhotos', 'projects'])
            ->with(['photos' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->paginate(12);

        return view('admin.galeria.index', compact('albums'));
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

        foreach ($request->file('images', []) as $index => $file) {
            $this->createPhotoFromUpload($album, $file, [
                'title' => $this->titleFromFile($file),
                'sort_order' => $index,
                'active' => true,
            ]);
        }

        return redirect()
            ->route('admin.galeria.edit', $album)
            ->with('success', 'Album criado com sucesso. Agora voce pode gerenciar as fotos.');
    }

    public function edit(GalleryAlbum $gallery)
    {
        $gallery->load([
            'photos' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
            'projects',
        ]);

        $projects = Project::active()->get();
        $uploadLimitMb = Setting::uploadLimitMb('image');

        return view('admin.galeria.edit', [
            'album' => $gallery,
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
            $photos[] = $this->createPhotoFromUpload($gallery, $file, [
                'title' => $validated['title'] ?? $this->titleFromFile($file),
                'description' => $validated['description'] ?? null,
                'sort_order' => $baseOrder + $index,
                'active' => $request->has('active') ? $request->boolean('active') : true,
            ]);
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
            $this->deletePublicFile($photo->image);
            $validated = array_merge($validated, $this->imagePayload($request->file('image'), $gallery));
        }

        $validated['active'] = $request->boolean('active');
        $photo->update($validated);

        return $this->jsonOrBack($request, [
            'success' => true,
            'message' => 'Foto atualizada com sucesso.',
            'photo' => $this->photoJson($photo->fresh()),
        ], route('admin.galeria.edit', $gallery));
    }

    public function destroyPhoto(Request $request, GalleryAlbum $gallery, GalleryPhoto $photo)
    {
        $this->abortIfPhotoDoesNotBelongToAlbum($gallery, $photo);

        $this->deletePublicFile($photo->image);
        $photo->delete();

        return $this->jsonOrBack($request, [
            'success' => true,
            'message' => 'Foto excluida com sucesso.',
        ], route('admin.galeria.edit', $gallery));
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

    private function createPhotoFromUpload(GalleryAlbum $album, UploadedFile $file, array $overrides = []): GalleryPhoto
    {
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
            'width' => $width,
            'height' => $height,
            'size_kb' => (int) ceil($file->getSize() / 1024),
        ];
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
