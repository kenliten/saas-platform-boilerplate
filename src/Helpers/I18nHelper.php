<?php

if (!function_exists('__')) {
    function __($key, $replacements = [])
    {
        static $translations = [];
        $locale = current_locale();
        if (!isset($translations[$locale])) {
            $path = __DIR__ . "/../Config/i18n/{$locale}.json";
            if (file_exists($path)) {
                $json = file_get_contents($path);
                $translations[$locale] = json_decode($json, true) ?? [];
            } else {
                $translations[$locale] = [];
            }
        }

        // Fetch line
        $line = $translations[$locale][$key] ?? $key;

        // Replace placeholders
        foreach ($replacements as $placeholder => $value) {
            $line = str_replace(':' . $placeholder, $value, $line);
        }

        return $line;
    }
}
