<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\TransparencyDocument;
use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncTransparencyFromDrive extends Command
{
    protected $signature = 'transparency:sync-drive {--dry-run : Apenas simula, nao altera o banco}';

    protected $description = 'Sincroniza documentos do Google Drive com a tabela transparency_documents';

    public function handle(GoogleDriveService $drive): int
    {
        if (! Setting::get('google_drive_enabled')) {
            $this->error('Google Drive nao esta ativado nas configuracoes.');
            return self::FAILURE;
        }

        if (! $drive->isEnabled()) {
            $this->error('Google Drive nao esta configurado. Verifique o arquivo de credenciais.');
            return self::FAILURE;
        }

        $rootFolderId = Setting::get('google_drive_folder_id');

        if (! $rootFolderId) {
            $this->error('ID da pasta raiz nao esta configurado nas configuracoes.');
            return self::FAILURE;
        }

        $dryRun = $this->option('dry-run');
        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        // 1) Listar pastas (categorias) na raiz
        $folders = $drive->listFolders($rootFolderId);

        if (empty($folders)) {
            $this->warn('Nenhuma pasta encontrada na raiz do Google Drive.');
            return self::SUCCESS;
        }

        $this->info('Pastas encontradas: ' . count($folders));

        // Coletar todos os fileIds existentes no banco que vieram do Drive
        $existingDriveIds = TransparencyDocument::where('source', 'drive')
            ->pluck('id', 'google_drive_file_id')
            ->toArray();

        $foundDriveIds = [];

        foreach ($folders as $folder) {
            $category = trim($folder->getName());
            $folderId = $folder->getId();

            $this->info("Processando categoria: {$category}");

            // Tentar extrair ano do nome da pasta (ex: "2025", "Relatorios 2024")
            $year = $this->extractYearFromName($category);

            // Listar arquivos dentro da pasta
            $files = $drive->listFiles($folderId);

            foreach ($files as $file) {
                $fileId = $file->getId();
                $fileName = $file->getName();
                $foundDriveIds[$fileId] = true;

                $downloadUrl = $drive->resolveDownloadUrl($file);
                $createdTime = $file->getCreatedTime() ? new \DateTime($file->getCreatedTime()) : now();

                if (isset($existingDriveIds[$fileId])) {
                    $doc = TransparencyDocument::find($existingDriveIds[$fileId]);

                    // Atualizar apenas se o link mudou (o nome raramente muda no Drive sem mudar o ID)
                    if ($doc->google_drive_url !== $downloadUrl || $doc->title !== $fileName) {
                        if (! $dryRun) {
                            $doc->update([
                                'title' => $fileName,
                                'google_drive_url' => $downloadUrl,
                                'file_path' => $downloadUrl,
                                'category' => $category,
                                'year' => $year,
                            ]);
                        }
                        $updatedCount++;
                        $this->line("  Atualizado: {$fileName}");
                    } else {
                        $skippedCount++;
                    }
                } else {
                    if (! $dryRun) {
                        TransparencyDocument::create([
                            'title' => $fileName,
                            'description' => null,
                            'file_path' => $downloadUrl,
                            'google_drive_file_id' => $fileId,
                            'google_drive_url' => $downloadUrl,
                            'source' => 'drive',
                            'category' => $category,
                            'year' => $year,
                            'published_at' => $createdTime->format('Y-m-d'),
                            'active' => true,
                        ]);
                    }
                    $createdCount++;
                    $this->line("  Criado: {$fileName}");
                }
            }
        }

        // 2) Desativar documentos do Drive que nao existem mais
        $missingIds = array_diff_key($existingDriveIds, $foundDriveIds);
        if (! empty($missingIds)) {
            $this->warn('Documentos removidos do Drive detectados: ' . count($missingIds));
            if (! $dryRun) {
                TransparencyDocument::whereIn('google_drive_file_id', array_keys($missingIds))
                    ->update(['active' => false]);
            }
        }

        $this->newLine();
        $this->info('Sincronizacao concluida.');
        $this->table(
            ['Metrica', 'Valor'],
            [
                ['Criados', $createdCount],
                ['Atualizados', $updatedCount],
                ['Ignorados (sem alteracao)', $skippedCount],
                ['Removidos/desativados', count($missingIds)],
            ]
        );

        if ($dryRun) {
            $this->warn('Modo simulacao (dry-run): nenhuma alteracao foi salva no banco.');
        }

        return self::SUCCESS;
    }

    private function extractYearFromName(string $name): int
    {
        if (preg_match('/\b(20\d{2})\b/', $name, $matches)) {
            return (int) $matches[1];
        }

        return (int) date('Y');
    }
}
