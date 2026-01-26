<?php

if (!function_exists('theme')) {
    /**
     * Get a theme setting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function theme($key, $default = null)
    {
        static $theme = null;

        if ($theme === null) {
            $theme = require __DIR__ . '/../Config/theme.php';
        }

        $keys = explode('.', $key);
        $value = $theme;

        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }

        return $value;
    }
}
