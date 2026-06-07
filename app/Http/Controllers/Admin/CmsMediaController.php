<?php

namespace App\Http\Controllers\Admin;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CmsMediaRequest;
use App\Models\CmsMedia;
use App\Services\Cms\CmsAuditService;
use App\Services\Cms\CmsCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CmsMediaController extends Controller
{
    protected CmsCacheService $cacheService;
    protected CmsAuditService $auditService;

    public function __construct(CmsCacheService $cacheService, CmsAuditService $auditService)
    {
        $this->cacheService = $cacheService;
        $this->auditService = $auditService;
    }

    public function index(Request $request): View
    {
        $query = CmsMedia::with('user');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%")
                  ->orWhere('original_name', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('type')) {
            if ($type === 'image') {
                $query->images();
            } elseif ($type === 'document') {
                $query->documents();
            } else {
                $query->byType($type);
            }
        }

        $media = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.cms.media.index', compact('media'));
    }

    public function upload(CmsMediaRequest $request): RedirectResponse|JsonResponse
    {
        $file = $request->file('file');
        $path = $file->store(config('cms.uploads.path', 'uploads') . '/media', 'public');

        $media = CmsMedia::create([
            'title' => $request->title,
            'alt_text' => $request->alt_text,
            'caption' => $request->caption,
            'credit' => $request->credit,
            'description' => $request->description,
            'filename' => $file->hashName(),
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'disk' => 'public',
            'user_id' => auth()->id(),
            'is_active' => true,
        ]);

        $this->auditService->logUpload('cms_media', $media);
        $this->cacheService->clearMediaCache();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Arquivo enviado com sucesso!', 'data' => $media]);
        }

        return redirect()->route('admin.cms.media.index')->with('success', 'Arquivo enviado com sucesso!');
    }

    public function update(CmsMediaRequest $request, CmsMedia $cmsMedium): RedirectResponse|JsonResponse
    {
        $oldValues = $cmsMedium->toArray();
        $cmsMedium->update($request->validated());

        $this->auditService->logUpdate('cms_media', $cmsMedium, $oldValues, $cmsMedium->fresh()->toArray());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Mídia atualizada com sucesso!', 'data' => $cmsMedium->fresh()]);
        }

        return redirect()->route('admin.cms.media.index')->with('success', 'Mídia atualizada com sucesso!');
    }

    public function destroy(CmsMedia $cmsMedium): RedirectResponse|JsonResponse
    {
        Storage::disk($cmsMedium->disk ?? 'public')->delete($cmsMedium->path);

        if ($cmsMedium->thumbnail_path) {
            Storage::disk($cmsMedium->disk ?? 'public')->delete($cmsMedium->thumbnail_path);
        }

        $cmsMedium->delete();

        $this->auditService->logDelete('cms_media', $cmsMedium);
        $this->cacheService->clearMediaCache();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Mídia excluída com sucesso!']);
        }

        return redirect()->route('admin.cms.media.index')->with('success', 'Mídia excluída com sucesso!');
    }

    public function getMediaJson(Request $request): JsonResponse
    {
        $query = CmsMedia::where('is_active', true);

        if ($type = $request->get('type')) {
            $query->where('mime_type', 'like', $type . '/%');
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('original_name', 'like', "%{$search}%");
            });
        }

        $media = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $media->items(),
            'pagination' => [
                'total' => $media->total(),
                'per_page' => $media->perPage(),
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
            ],
        ]);
    }

    public function getMediaById(int $id): JsonResponse
    {
        $media = CmsMedia::find($id);

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Mídia não encontrada.'], 404);
        }

        return response()->json(['success' => true, 'data' => $media]);
    }
}
