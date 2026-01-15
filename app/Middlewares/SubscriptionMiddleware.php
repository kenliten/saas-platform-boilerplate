<?php

namespace App\Middlewares;

use App\Core\Session;
use App\Services\LimitService;

class SubscriptionMiddleware
{
    public function handle()
    {
        // Only check for logged-in users
        if (!Session::has('user_id')) {
            return; 
        }

        // Allow Admin bypass
        if (user_role() === 'admin') {
            return;
        }

        $userId = Session::get('user_id');
        $isPro = is_pro();

        if (!$isPro) {
            $uri = $_SERVER['REQUEST_URI'];
            $path = parse_url($uri, PHP_URL_PATH);

            // Pro Only Tools (Blocked for Free)
            $proOnlyPaths = [
                '/budgets',
                '/transactions',
                '/savings-logs',
                '/cashflow',
                '/monthly-reflection'
            ];

            foreach ($proOnlyPaths as $proPath) {
                if ($path === $proPath || strpos($path, $proPath . '/') === 0) {
                    header('Location: /buy?msg=upgrade_required');
                    exit;
                }
            }
        }
    }
}