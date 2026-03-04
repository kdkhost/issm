<?php
$senha = 'Admin@ISSM2024!';
$hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
echo $hash . PHP_EOL;
