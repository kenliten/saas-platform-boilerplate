<?php

// Simple script to rotate logs if we were using a custom file logger.
// Since we rely on PHP's error_log or web server logs usually in zero-dep setup,
// this might rotate specific app logs if we wrote them.

$logDir = __DIR__ . '/../storage/logs';
$logFile = "$logDir/app.log";

if (file_exists($logFile)) {
    $date = date('Y-m-d');
    $rotated = "$logDir/app-$date.log";
    rename($logFile, $rotated);
    
    // Gzip
    $data = file_get_contents($rotated);
    $gzdata = gzencode($data, 9);
    file_put_contents("$rotated.gz", $gzdata);
    unlink($rotated);
    
    echo "Rotated $logFile to $rotated.gz\n";
} else {
    echo "No log file to rotate.\n";
}
