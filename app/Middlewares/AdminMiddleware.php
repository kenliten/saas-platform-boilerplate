<?php

namespace App\Middlewares;

use App\Core\Session;

class AdminMiddleware
{
    public function handle()
    {
        // Ensure user is logged in first
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        // Check for admin role
        if (user_role() !== 'admin') {
            // Return 403 Forbidden
            http_response_code(403);
            echo "403 Forbidden - Access Denied";
            exit;
        }
    }
}
