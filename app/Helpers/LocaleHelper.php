<?php

use App\Core\Session;

if (!function_exists('current_locale')) {
    function current_locale()
    {
        if (session_status() === PHP_SESSION_NONE) {
            Session::start();
        }

        // Check GET param and update session
        if (isset($_GET['lang'])) {
            $lang = preg_replace('/[^a-z-]/', '', $_GET['lang']);
            if (file_exists(__DIR__ . "/../Config/i18n/{$lang}.json")) {
                Session::set('app_locale', $lang);
                return $lang;
            }
        } else {
            $browserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (file_exists(__DIR__ . "/../Config/i18n/{$browserLanguage}.json")) {
                Session::set('app_locale', $browserLanguage);
                return $browserLanguage;
            }
        }

        return Session::get('app_locale') ?? env('APP_LOCALE', 'en');
    }
}
