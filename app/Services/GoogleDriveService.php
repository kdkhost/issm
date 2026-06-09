<?php

namespace App\Services;

use App\Models\Setting;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected ?Drive $service = null;
    protected bool $enabled = false;

    public function __construct()
    {
        if (! Setting::get('google_drive_enabled')) {
            return;
        }

        $credentialsPath = storage_path('app/google/credentials.json');

        if (! file_exists($credentialsPath)) {
            Log::warning('GoogleDriveService: credenciais não encontradas em ' . $credentialsPath);
            return;
        }

        try {
            $client = new Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Drive::DRIVE);
            $this->service = new Drive($client);
            $this->enabled = true;
        } catch (\Throwable $e) {
            Log::error('GoogleDriveService: falha ao inicializar: ' . $e->getMessage());
        }
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Lista os itens (pastas ou arquivos) dentro de um folderId.
     *
     * @return array<int, \Google\Service\Drive\DriveFile>
     */
    public function listItems(string $folderId, string $mimeTypeFilter = ''): array
    {
        if (! $this->enabled) {
            return [];
        }

        $query = "'{$folderId}' in parents and trashed=false";

        if ($mimeTypeFilter) {
            $query .= " and mimeType='{$mimeTypeFilter}'";
        }

        try {
            $response = $this->service->files->listFiles([
                'q' => $query,
                'fields' => 'nextPageToken, files(id, name, mimeType, webViewLink, webContentLink, createdTime, modifiedTime)',
                'orderBy' => 'name',
                'pageSize' => 1000,
            ]);

            return $response->getFiles() ?? [];
        } catch (\Throwable $e) {
            Log::error('GoogleDriveService: erro ao listar itens: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lista apenas pastas dentro do folderId.
     *
     * @return array<int, \Google\Service\Drive\DriveFile>
     */
    public function listFolders(string $folderId): array
    {
        return $this->listItems($folderId, 'application/vnd.google-apps.folder');
    }

    /**
     * Lista apenas arquivos (exclui pastas e shortcuts) dentro do folderId.
     *
     * @return array<int, \Google\Service\Drive\DriveFile>
     */
    public function listFiles(string $folderId): array
    {
        if (! $this->enabled) {
            return [];
        }

        $query = "'{$folderId}' in parents and trashed=false and mimeType!='application/vnd.google-apps.folder' and mimeType!='application/vnd.google-apps.shortcut'";

        try {
            $response = $this->service->files->listFiles([
                'q' => $query,
                'fields' => 'nextPageToken, files(id, name, mimeType, webViewLink, webContentLink, createdTime, modifiedTime)',
                'orderBy' => 'name',
                'pageSize' => 1000,
            ]);

            return $response->getFiles() ?? [];
        } catch (\Throwable $e) {
            Log::error('GoogleDriveService: erro ao listar arquivos: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Cria uma nova pasta no Google Drive.
     */
    public function createFolder(string $name, string $parentFolderId): ?string
    {
        if (! $this->enabled) {
            return null;
        }

        try {
            $file = new \Google\Service\Drive\DriveFile([
                'name' => $name,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$parentFolderId],
            ]);

            $created = $this->service->files->create($file, ['fields' => 'id']);
            return $created->getId();
        } catch (\Throwable $e) {
            Log::error('GoogleDriveService: erro ao criar pasta: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Renomeia uma pasta no Google Drive.
     */
    public function renameFolder(string $folderId, string $newName): bool
    {
        if (! $this->enabled) {
            return false;
        }

        try {
            $file = new \Google\Service\Drive\DriveFile(['name' => $newName]);
            $this->service->files->update($folderId, $file);
            return true;
        } catch (\Throwable $e) {
            Log::error('GoogleDriveService: erro ao renomear pasta: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Move uma pasta para a lixeira (deletar).
     */
    public function deleteFolder(string $folderId): bool
    {
        if (! $this->enabled) {
            return false;
        }

        try {
            $this->service->files->delete($folderId);
            return true;
        } catch (\Throwable $e) {
            Log::error('GoogleDriveService: erro ao deletar pasta: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtem o link direto de download/exportacao para um arquivo.
     * Para Google Docs/Sheets/Slides retorna o link de exportacao em PDF.
     * Para arquivos binarios retorna o webContentLink.
     */
    public function resolveDownloadUrl(\Google\Service\Drive\DriveFile $file): string
    {
        $mime = $file->getMimeType();
        $id = $file->getId();

        // Google Workspace files precisam de exportacao
        $exportMap = [
            'application/vnd.google-apps.document' => 'application/pdf',
            'application/vnd.google-apps.spreadsheet' => 'application/pdf',
            'application/vnd.google-apps.presentation' => 'application/pdf',
            'application/vnd.google-apps.drawing' => 'image/png',
        ];

        if (isset($exportMap[$mime])) {
            return "https://www.googleapis.com/drive/v3/files/{$id}/export?mimeType=" . urlencode($exportMap[$mime]) . "&alt=media";
        }

        // Arquivos binarios: usar o link direto de download
        if ($file->getWebContentLink()) {
            return $file->getWebContentLink();
        }

        // Fallback para link de visualizacao
        return $file->getWebViewLink() ?? "https://drive.google.com/file/d/{$id}/view";
    }
}
