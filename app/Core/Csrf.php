<?php

namespace App\Core;

class Csrf
{
    public static function generate()
    {
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
    }

    public static function token()
    {
        self::generate();
        return Session::get('csrf_token');
    }

    public static function verify($requestToken)
    {
        $token = Session::get('csrf_token');
        return $token && hash_equals($token, $requestToken);
    }

    public static function field()
    {
        $token = self::token();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
