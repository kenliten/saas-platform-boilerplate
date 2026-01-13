<?php

namespace App\Core;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session params
            $lifetime = 60 * 60 * 24; // 1 day
            $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
            // Strip port if present
            if (strpos($domain, ':') !== false) {
                $domain = explode(':', $domain)[0];
            }
            
            session_set_cookie_params([
                'lifetime' => $lifetime,
                'path' => '/',
                'domain' => $domain, 
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function destroy()
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function remove($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function regenerate()
    {
        self::start();
        session_regenerate_id(true);
    }
}
