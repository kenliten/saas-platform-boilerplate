<?php

require_once __DIR__ . '/../app/bootstrap.php';

$host = getenv('DB_HOST') ?: '127.0.0.1';
$db   = getenv('DB_DATABASE') ?: 'saas_db';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

$backupDir = __DIR__ . '/../storage/backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$filename = 'db-' . date('Y-m-d-His') . '.sql';
$filepath = "$backupDir/$filename";

echo "Backing up database '$db' to '$filepath'...\n";

// Using mysqldump (must be in PATH)
// Warning: Putting password in command line is visible in process list, but acceptable for simple local dev tools.
// For production, use defaults-extra-file.
$cmd = sprintf(
    'mysqldump --host=%s --user=%s --password=%s %s > %s',
    escapeshellarg($host),
    escapeshellarg($user),
    escapeshellarg($pass),
    escapeshellarg($db),
    escapeshellarg($filepath)
);

system($cmd, $retval);

if ($retval === 0) {
    echo "Backup successful.\n";
} else {
    echo "Backup failed. Return code: $retval\n";
}
