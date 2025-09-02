<?php
require __DIR__ . '/../src/Helpers.php';

$pdo = new PDO(
    'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_NAME') . ';charset=utf8mb4',
    env('DB_USER'),
    env('DB_PASS'),
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$sql = file_get_contents(__DIR__ . '/../sql/schema.sql');
$pdo->exec($sql);

echo "Миграции выполнены!";
