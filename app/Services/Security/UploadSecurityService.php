<?php

namespace App\Services\Security;

use Illuminate\Http\UploadedFile;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */
class UploadSecurityService
{
    protected array $defaultAllowedExtensions = [
        'jpg', 'jpeg', 'png', 'webp', 'pdf',
        'doc', 'docx', 'xls', 'xlsx',
        'gif', 'svg',
    ];

    protected array $dangerousExtensions = [
        'php', 'php3', 'php4', 'php5', 'php7', 'pht', 'phtml',
        'exe', 'bat', 'cmd', 'com', 'msi',
        'sh', 'bash', 'zsh',
        'pl', 'py', 'rb', 'asp', 'aspx', 'jsp',
        'cgi', 'htaccess', 'htpasswd',
        'jar', 'war', 'scr',
        'vbs', 'vbe', 'js', 'jse',
        'wsf', 'wsh', 'ps1', 'psm1',
    ];

    protected array $imageMimeTypes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'image/svg+xml', 'image/bmp', 'image/tiff',
    ];

    protected array $documentMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain', 'text/csv',
    ];

    public function validateUpload(UploadedFile $file, array $allowedExtensions = []): array
    {
        if (!$file->isValid()) {
            return [
                'valid' => false,
                'message' => 'O upload do arquivo falhou.',
                'mime' => null,
            ];
        }

        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = !empty($allowedExtensions) ? $allowedExtensions : $this->defaultAllowedExtensions;

        if (!in_array($ext, $allowed, true)) {
            return [
                'valid' => false,
                'message' => "Extensão '{$ext}' não é permitida.",
                'mime' => null,
            ];
        }

        if ($this->isDangerousFile($file)) {
            return [
                'valid' => false,
                'message' => 'Arquivo suspeito detectado.',
                'mime' => null,
            ];
        }

        $maxSize = $this->getMaxFileSize();
        if ($file->getSize() > $maxSize) {
            $maxMb = round($maxSize / 1048576, 2);
            return [
                'valid' => false,
                'message' => "O arquivo excede o tamanho máximo de {$maxMb}MB.",
                'mime' => null,
            ];
        }

        $realMime = $this->getRealMimeType($file);

        if ($ext === 'svg') {
            $content = file_get_contents($file->getRealPath());
            if (!$this->checkSvgSecurity($content)) {
                return [
                    'valid' => false,
                    'message' => 'SVG suspeito detectado.',
                    'mime' => $realMime,
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Arquivo validado com sucesso.',
            'mime' => $realMime,
        ];
    }

    public function isDangerousFile(UploadedFile $file): bool
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, $this->dangerousExtensions, true)) {
            return true;
        }

        $name = $file->getClientOriginalName();
        if (preg_match('/\.(php|phtml|php\d?)(\.[a-z0-9]+)?$/i', $name)) {
            return true;
        }

        $doubleExtPattern = '/\.(jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx)\.(php|exe|sh|phtml|asp|jsp|pl|py|rb)/i';
        if (preg_match($doubleExtPattern, $name)) {
            return true;
        }

        $content = file_get_contents($file->getRealPath());
        if ($content === false) {
            return true;
        }

        $dangerousSignatures = [
            '<?php', '<?=', '<?PHP',
            '<script language="php"',
            '#!/usr/bin/php',
            '#!/bin/sh',
            '#!/bin/bash',
            'MZ', // PE/EXE header
        ];

        foreach ($dangerousSignatures as $sig) {
            if (str_starts_with($content, $sig)) {
                return true;
            }
        }

        return false;
    }

    public function sanitizeFilename(string $name): string
    {
        $name = mb_strtolower($name, 'UTF-8');

        $name = preg_replace('/[^\w\s\.\-\x80-\xFF]/u', '', $name);

        $name = preg_replace('/[\s]+/', '_', $name);

        $name = preg_replace('/[_\-.]+/', '_', $name);

        $name = preg_replace('/\.(?=.*\.)/', '_', $name);

        $name = trim($name, '._-');

        if (strlen($name) > 200) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $base = pathinfo($name, PATHINFO_FILENAME);
            $base = substr($base, 0, 190);
            $name = $base . '.' . $ext;
        }

        if (empty($name)) {
            $name = 'file_' . time();
        }

        return $name;
    }

    public function getRealMimeType(UploadedFile $file): string
    {
        if (!file_exists($file->getRealPath())) {
            return 'application/octet-stream';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        return $mime ?: 'application/octet-stream';
    }

    public function getMaxFileSize(): int
    {
        $configSize = config('cms.uploads.max_size');
        if (is_numeric($configSize) && $configSize > 0) {
            return (int) $configSize;
        }

        return 10 * 1024 * 1024;
    }

    public function isImage(UploadedFile $file): bool
    {
        $mime = $this->getRealMimeType($file);
        return in_array($mime, $this->imageMimeTypes, true);
    }

    public function isDocument(UploadedFile $file): bool
    {
        $mime = $this->getRealMimeType($file);
        return in_array($mime, $this->documentMimeTypes, true);
    }

    public function checkSvgSecurity(string $content): bool
    {
        if (stripos($content, '<script') !== false) {
            return false;
        }

        if (preg_match('/<[a-zA-Z]+\s+[^>]*on\w+\s*=/i', $content)) {
            return false;
        }

        if (stripos($content, '<foreignObject') !== false || stripos($content, '<foreignobject') !== false) {
            return false;
        }

        if (preg_match('/<!ENTITY/i', $content)) {
            return false;
        }

        if (stripos($content, 'javascript:') !== false || stripos($content, 'data:') !== false) {
            return false;
        }

        return true;
    }

    public function generateHash(UploadedFile $file): string
    {
        return md5_file($file->getRealPath());
    }

    public function getStoragePath(string $extension): string
    {
        return 'uploads/' . date('Y/m');
    }
}
