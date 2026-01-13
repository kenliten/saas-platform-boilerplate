<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "Tables in database:\n";
foreach ($tables as $table) {
    echo "- $table\n";
}
