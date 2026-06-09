<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class GoogleDriveFolderController extends Controller
{
    public function index(GoogleDriveService $drive)
    {
        if (! $drive->isEnabled()) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Google Drive não está configurado. Va em Configurações > Google Drive.');
        }

        $rootFolderId = Setting::get('google_drive_folder_id');

        if (! $rootFolderId) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'ID da pasta raiz do Google Drive não está configurado.');
        }

        $folders = $drive->listFolders($rootFolderId);

        return view('admin.drive-folders.index', compact('folders'));
    }

    public function store(Request $request, GoogleDriveService $drive)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $rootFolderId = Setting::get('google_drive_folder_id');
        $folderId = $drive->createFolder($request->name, $rootFolderId);

        if ($folderId) {
            return redirect()->back()->with('success', "Pasta '{$request->name}' criada no Google Drive.");
        }

        return redirect()->back()->with('error', 'Falha ao criar pasta. Verifique se a Service Account tem permissao de Editor no Google Drive.');
    }

    public function update(Request $request, GoogleDriveService $drive, string $folderId)
    {
        $request->validate(['name' => 'required|string|max:255']);

        if ($drive->renameFolder($folderId, $request->name)) {
            return redirect()->back()->with('success', "Pasta renomeada para '{$request->name}'.");
        }

        return redirect()->back()->with('error', 'Falha ao renomear pasta. Verifique as permissoes da Service Account.');
    }

    public function destroy(GoogleDriveService $drive, string $folderId)
    {
        if ($drive->deleteFolder($folderId)) {
            return redirect()->back()->with('success', 'Pasta movida para a lixeira do Google Drive.');
        }

        return redirect()->back()->with('error', 'Falha ao deletar pasta. Verifique as permissoes da Service Account.');
    }
}
