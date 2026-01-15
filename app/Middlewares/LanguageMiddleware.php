<?php

namespace App\Middlewares;

use App\Core\Session;

class LanguageMiddleware
{
    public function handle()
    {
        if (Session::has('user_id')) {
            $user = user();
            if ($user && isset($user['language'])) {
                Session::set('app_locale', $user['language']);
                return;
            }
        }
    }
}
