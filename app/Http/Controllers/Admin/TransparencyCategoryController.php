<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransparencyCategory;
use App\Models\Setting;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class TransparencyCategoryController extends Controller
{
    public function index()
    {
        $categories = TransparencyCategory::ordered()->get();
        return view('admin.transparency.categories.index', compact('categories'));
    }

    public function store(Request $request, GoogleDriveService $drive)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Se o Google Drive estiver configurado, cria a pasta automaticamente
        $rootFolderId = Setting::get('google_drive_folder_id');
        if ($drive->isEnabled() && $rootFolderId) {
            $folderId = $drive->createFolder($validated['name'], $rootFolderId);
            if ($folderId) {
                $validated['google_drive_folder_id'] = $folderId;
            }
        }

        TransparencyCategory::create($validated);

        return redirect()->route('admin.transparency-categories.index')
            ->with('success', "Categoria '{$validated['name']}' criada" . (isset($validated['google_drive_folder_id']) ? ' e pasta criada no Google Drive.' : '.'));
    }

    public function update(Request $request, TransparencyCategory $category, GoogleDriveService $drive)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);

        // Se renomeou e tem pasta no Drive, renomeia a pasta também
        if ($drive->isEnabled() && $category->google_drive_folder_id && $validated['name'] !== $category->name) {
            $drive->renameFolder($category->google_drive_folder_id, $validated['name']);
        }

        $category->update($validated);

        return redirect()->route('admin.transparency-categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(TransparencyCategory $category, GoogleDriveService $drive)
    {
        // Se tem pasta no Drive, move para lixeira
        if ($drive->isEnabled() && $category->google_drive_folder_id) {
            $drive->deleteFolder($category->google_drive_folder_id);
        }

        // Desassocia documentos (remove categoria_id)
        $category->documents()->update(['category_id' => null]);
        $category->delete();

        return redirect()->route('admin.transparency-categories.index')
            ->with('success', 'Categoria removida com sucesso!');
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:transparency_categories,id',
        ]);

        foreach ($validated['order'] as $index => $categoryId) {
            TransparencyCategory::where('id', $categoryId)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
