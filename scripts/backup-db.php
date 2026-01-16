<?php

use App\Core\Database;

require_once __DIR__ . '/../app/bootstrap.php';

$db = Database::getConnection();

$backupDir = __DIR__ . '/../storage/backups';
$backupContent = "";

if (!is_dir($backupDir)) {
    echo "Backup directory does not exist. Creating...\n";
    mkdir($backupDir, 0755, true);
}

$filename = 'db-' . date('Y-m-d-His') . '.sql';
$filepath = "$backupDir/$filename";

$tables = $db->query("SHOW TABLES")->fetchAll();
echo "Found " . count($tables) . " tables.\nStarting backup...\n";

function addQuotes($str, $isColName = false)
{
    if (!$str) {
        return 'NULL';
    }
    if (is_numeric($str)) {
        return $str;
    }
    return $isColName ? "'$str'" : "`$str`";
}

function rowsToSQLInsert($rows, $t)
{
    $sql = "";
    foreach ($rows as $row) {
        unset($row['id']);
        $sql .= "INSERT INTO `$t` (" . implode(", ", array_map(function ($str) {
            return addQuotes($str);
        }, array_keys($row))) . ") VALUES (" . implode(", ", array_map(function ($str) {
            return addQuotes($str, true);
        }, array_values($row))) . ");\n";
    }
    return $sql;
}

foreach ($tables as $table) {
    $table = $table['Tables_in_' . env('DB_DATABASE')];
    $backupContent .= "DROP TABLE IF EXISTS `$table`;\n\n";
    $backupContent .= $db->query("SHOW CREATE TABLE `$table`")->fetchAll()[0]['Create Table'] . ";\n\n";
    $backupContent .= rowsToSQLInsert($db->query("SELECT * FROM `$table`;")->fetchAll(), $table) . "\n";
}

file_put_contents($filepath, $backupContent);
echo "Backup completed.\n";
