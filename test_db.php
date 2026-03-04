<?php
try {
    $pdo = new PDO(
        'mysql:host=15.235.57.3;port=3306;dbname=issmorg_renata;charset=utf8mb4',
        'issmorg_renata',
        'issmorg_renata!',
        [PDO::ATTR_TIMEOUT => 10, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo 'CONEXAO OK: MySQL ' . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . PHP_EOL;
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo 'TABELAS EXISTENTES (' . count($tables) . '): ' . PHP_EOL;
    foreach ($tables as $t) echo '  - ' . $t . PHP_EOL;
} catch (Exception $e) {
    echo 'ERRO: ' . $e->getMessage() . PHP_EOL;
}
