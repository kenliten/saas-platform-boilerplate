<?php

use App\Core\Session;
use App\Core\Database;

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);
        if ($value === false) {
            return $_ENV[$key] ?? $default;
        }
        return $value;
    }
}

if (!function_exists('current_locale')) {
    function current_locale() {
        if (session_status() === PHP_SESSION_NONE) {
            Session::start();
        }
        
        // Check GET param and update session
        if (isset($_GET['lang'])) {
            $lang = preg_replace('/[^a-z-]/', '', $_GET['lang']); // Sanitize
            // Validate file exists
            if (file_exists(__DIR__ . "/Config/i18n/{$lang}.json")) {
                Session::set('app_locale', $lang);
                return $lang;
            }
        }

        // Return Session or Env Default
        return Session::get('app_locale') ?? env('APP_LOCALE', 'en');
    }
}

if (!function_exists('__')) {
    function __($key, $replacements = []) {
        static $translations = [];
        $locale = current_locale();

        // Load translations if not already loaded for this locale
        if (!isset($translations[$locale])) {
            $path = __DIR__ . "/Config/i18n/{$locale}.json";
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

if (!function_exists('is_authenticated')) {
    function is_authenticated() {
        return Session::has('user_id');
    }
}

if (!function_exists('user_id')) {
    function user_id() {
        return Session::get('user_id');
    }
}

if (!function_exists('user_role')) {
    function user_role() {
        return Session::get('role') ?? 'guest';
    }
}

if (!function_exists('user')) {
    function user() {
        static $user = null;
        if ($user) return $user;

        $id = user_id();
        if (!$id) return null;

        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM users WHERE id = ?", [$id]);
        $user = $stmt->fetch();
        return $user;
    }
}

if (!function_exists('user_payment_status')) {
    function user_payment_status() {
        static $plan = null;
        if ($plan) return $plan;

        $user = user();
        if (!$user) return 'free'; // Default or none

        $db = Database::getConnection();
        // Assuming account_id is on user, and accounts table has 'plan' column (slug)
        $stmt = $db->query("SELECT plan FROM accounts WHERE id = ?", [$user['account_id']]);
        $result = $stmt->fetch();
        
        $plan = $result['plan'] ?? 'free';
        return $plan;
    }
}
