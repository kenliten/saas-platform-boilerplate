<?php

namespace App\Middlewares;

use App\Core\Session;

class AuthMiddleware
{
    public function handle()
    {
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }
    }
}
