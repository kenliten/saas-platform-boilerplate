<?php

require_once __DIR__ . '/../app/bootstrap.php';

echo "Starting daily backup...\n";

require_once __DIR__ . '/backup-db.php';

$uploadsFile = "$backupDir/uploads-" . date('Y-m-d-His') . ".tar.gz";

echo "Backing up uploads to $uploadsFile...\n";
$uploadsDir = __DIR__ . '/../public/uploads';

if (is_dir($uploadsDir)) {
    $cmd = sprintf(
        'tar -czf %s -C %s uploads',
        escapeshellarg($uploadsFile),
        escapeshellarg(__DIR__ . '/../public')
    );
    system($cmd, $retval);
    if ($retval !== 0)
        echo "Warning: Uploads backup failed.\n";
} else {
    echo "No uploads directory found, skipping.\n";
}

$days = 7;
echo "Cleaning up backups older than $days days...\n";
$files = glob("$backupDir/*");
$now = time();
$deleted = 0;
foreach ($files as $file) {
    if (is_file($file)) {
        if ($now - filemtime($file) >= 60 * 60 * 24 * $days) {
            unlink($file);
            $deleted++;
            echo "Deleted old backup: " . basename($file) . "\n";
        }
    }
}

if ($deleted == 0) {
    echo "No backups were deleted.\n";
}

echo "Backup complete.\n";
