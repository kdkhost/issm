<?php

/**
 * Verifica se o deploy do CMS institucional está completo no servidor.
 * Uso: php tools/verify-cms-deploy.php
 */

$root = dirname(__DIR__);

$requiredFiles = [
    'app/Console/Commands/CmsMapPublicPages.php',
    'app/Console/Commands/CmsSyncPublicPageDefaults.php',
    'app/Console/Commands/CmsAuditPublicPageFields.php',
    'app/Services/Cms/CmsPageMapperService.php',
    'app/helpers.php',
    'database/migrations/2026_06_07_000001_create_cms_public_pages_tables.php',
    'app/Http/Controllers/Admin/CmsPublicPageController.php',
];

echo "=== Verificação CMS ISSM ===\n";
echo "Diretório: {$root}\n\n";

$missing = [];
foreach ($requiredFiles as $file) {
    $path = $root . '/' . $file;
    $ok = file_exists($path);
    echo ($ok ? '[OK] ' : '[FALTA] ') . $file . "\n";
    if (!$ok) {
        $missing[] = $file;
    }
}

echo "\n";

if (!file_exists($root . '/artisan')) {
    echo "ERRO: artisan não encontrado neste diretório.\n";
    echo "Execute este script na RAIZ do Laravel (onde fica artisan), não em public/.\n";
    exit(1);
}

if ($missing) {
    echo "Deploy incompleto. Arquivos ausentes: " . count($missing) . "\n";
    echo "Execute: git pull origin main\n";
    echo "Depois: composer dump-autoload -o\n";
    exit(1);
}

require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commands = array_filter(
    array_keys($kernel->all()),
    fn ($name) => str_starts_with($name, 'cms:')
);

echo "Comandos cms:* encontrados: " . count($commands) . "\n";
foreach ($commands as $cmd) {
    echo "  - {$cmd}\n";
}

if (count($commands) === 0) {
    echo "\nERRO: Nenhum comando cms:* registrado.\n";
    echo "Execute: php artisan optimize:clear && composer dump-autoload -o\n";
    exit(1);
}

echo "\nDeploy CMS OK.\n";
