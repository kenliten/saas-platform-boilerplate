<?php

namespace App;

// Config object to avoid using env() function in the code
// Use Config::get('app.name') instead of env('APP_NAME')

class Config
{
    private static $config = [
        // App
        "app" => [
            "name" => env('APP_NAME', 'SaaS Platform'),
            "version" => env('APP_VERSION'),
            "mode" => env('APP_MODE', 'single'),
            "url" => env('APP_URL'),
            "env" => env('APP_ENV'),
            "debug" => env('APP_DEBUG'),
            "locale" => env('APP_LOCALE'),
            "theme_color" => env('APP_THEME_COLOR'),
            "logo" => env('APP_LOGO'),
        ],
        // Database
        "db" => [
            "connection" => env('DB_CONNECTION'),
            "host" => env('DB_HOST'),
            "port" => env('DB_PORT'),
            "database" => env('DB_DATABASE'),
            "username" => env('DB_USERNAME'),
            "password" => env('DB_PASSWORD'),
        ],
        // Billing (stripe, paypal, none)
        "billing" => [
            "paypal_client_id" => env('PAYPAL_CLIENT_ID'),
            "paypal_secret" => env('PAYPAL_SECRET'),
        ],
        // Email Settings
        "mail" => [
            "from_address" => env('MAIL_FROM_ADDRESS'),
            "from_name" => env('MAIL_FROM_NAME'),
            "admin_owner" => env('ADMIN_OWNER'),
            "host" => env('MAIL_HOST'),
            "port" => env('MAIL_PORT'),
            "username" => env('MAIL_USERNAME'),
            "password" => env('MAIL_PASSWORD'),
        ],
    ];

    public static function get($key)
    {
        return self::$config[$key];
    }
}