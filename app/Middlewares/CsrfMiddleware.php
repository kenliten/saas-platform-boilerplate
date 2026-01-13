<?php

namespace App\Middlewares;

use App\Core\Csrf;

class CsrfMiddleware
{
    public function handle()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            
            if (!Csrf::verify($token)) {
                http_response_code(403);
                die('403 Forbidden - Invalid CSRF Token');
            }
        }
    }
}
