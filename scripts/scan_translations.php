<?php

/**
 * Translation Scanner Script
 * Scans app/Views recursively for __("key") or __('key') calls
 * and updates app/Config/i18n/*.json files with missing keys.
 */

$viewsDir = __DIR__ . '/../app/Views';
$i18nDir = __DIR__ . '/../app/Config/i18n';

// 1. Scan Views for unique keys
$keys = [];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

echo "Scanning views in $viewsDir...\n";

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        // Regex to match __('key') or __("key")
        // Handling optional spaces inside parenthesis and around matching quotes
        if (preg_match_all('/__\s*\(\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
            foreach ($matches[1] as $key) {
                $keys[] = $key;
            }
        }
    }
}

$keys = array_unique($keys);
sort($keys);

echo "Found " . count($keys) . " unique translation keys.\n";

// 2. Update JSON files
$jsonFiles = glob($i18nDir . '/*.json');

if (empty($jsonFiles)) {
    echo "No language files found in $i18nDir. Creating en.json...\n";
    file_put_contents($i18nDir . '/en.json', '{}');
    $jsonFiles[] = $i18nDir . '/en.json';
}

foreach ($jsonFiles as $jsonFile) {
    $lang = basename($jsonFile);
    echo "Processing $lang...\n";
    
    $current = json_decode(file_get_contents($jsonFile), true) ?? [];
    $added = 0;

    foreach ($keys as $key) {
        if (!array_key_exists($key, $current)) {
            $current[$key] = "__MISSING__";
            $added++;
        }
    }

    if ($added > 0) {
        // Sort keys alphabetically for neatness
        ksort($current);
        file_put_contents($jsonFile, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "  - Added $added missing keys.\n";
    } else {
        echo "  - No new keys.\n";
    }
}

echo "Done.\n";
