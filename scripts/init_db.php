<?php

require_once __DIR__ . '/../app/bootstrap.php';

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_DATABASE') ?: 'saas_db';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$db'");
    if ($stmt->fetchColumn() == 0) {
        echo "Creating database '$db'...\n";
        $pdo->exec("CREATE DATABASE `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Database created successfully.\n";
    } else {
        echo "Database '$db' already exists.\n";
    }
} catch (PDOException $e) {
    echo "DB Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
