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

$date = date('Y-m-d-His');
$dbFile = "$backupDir/db-$date.sql";
$uploadsFile = "$backupDir/uploads-$date.tar.gz";

echo "Starting daily backup...\n";

// 1. DB Backup
echo "Backing up database to $dbFile...\n";
$cmd = sprintf(
    'mysqldump --host=%s --user=%s --password=%s %s > %s',
    escapeshellarg($host),
    escapeshellarg($user),
    escapeshellarg($pass),
    escapeshellarg($db),
    escapeshellarg($dbFile)
);
system($cmd, $retval);
if ($retval !== 0) echo "Warning: DB backup failed.\n";

// 2. Uploads Backup
echo "Backing up uploads to $uploadsFile...\n";
// Assumes 'tar' is available (classic unix tool, available in Git Bash on Windows or Linux)
$uploadsDir = __DIR__ . '/../storage/uploads';
if (is_dir($uploadsDir)) {
    // Navigate to storage root to keep paths relative
    $cmd = sprintf(
        'tar -czf %s -C %s uploads',
        escapeshellarg($uploadsFile),
        escapeshellarg(__DIR__ . '/../storage')
    );
    system($cmd, $retval);
    if ($retval !== 0) echo "Warning: Uploads backup failed.\n";
} else {
    echo "No uploads directory found, skipping.\n";
}

// 3. Cleanup old backups (keep last 7 days)
$days = 7;
echo "Cleaning up backups older than $days days...\n";
$files = glob("$backupDir/*");
$now = time();
foreach ($files as $file) {
    if (is_file($file)) {
        if ($now - filemtime($file) >= 60 * 60 * 24 * $days) {
            unlink($file);
            echo "Deleted old backup: " . basename($file) . "\n";
        }
    }
}

echo "Backup complete.\n";
