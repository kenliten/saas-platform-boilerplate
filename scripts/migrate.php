<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

$db = Database::getInstance();
$pdo = $db->getConnection();

echo "Starting migrations...\n";

// 1. Ensure migrations table exists
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
");

// 2. Scan for migration files
$migrationDir = __DIR__ . '/../app/Migrations';
if (!is_dir($migrationDir)) {
    mkdir($migrationDir, 0755, true);
}

$files = scandir($migrationDir);
$migrationFiles = [];
foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
        $migrationFiles[] = $file;
    }
}
sort($migrationFiles);

// 3. Apply pending migrations
foreach ($migrationFiles as $file) {
    // Check if applied
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE filename = ?");
    $stmt->execute([$file]);
    if ($stmt->fetchColumn() > 0) {
        continue;
    }

    echo "Migrating: $file\n";

    $sql = file_get_contents($migrationDir . '/' . $file);
    
    try {
        // Multi-statement execution usually works in one go with PDO,
        // unless emulation is strictly off AND driver doesn't support it.
        // But for safety and better error reporting, simple splitting by ';' might be needed
        // OR just try standard exec if commands are simple.
        // We will try executing the whole block. Basic MySQL PDO supports multiple statements if enabled (which is often default).
        
        $pdo->exec($sql);
        
        $stmt = $pdo->prepare("INSERT INTO migrations (filename) VALUES (?)");
        $stmt->execute([$file]);
        
        echo "Migrated:  $file\n";
    } catch (Exception $e) {
        echo "Error in $file: " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "All migrations applied.\n";
