<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;

class HealthController extends BaseController
{
    public function check()
    {
        $status = 'ok';
        $checks = [];

        // 1. Database
        try {
            $pdo = Database::getConnection();
            $pdo->query("SELECT 1");
            $checks['database'] = 'ok';
        } catch (\Exception $e) {
            $status = 'error';
            $checks['database'] = 'failed: ' . $e->getMessage();
        }

        // 2. Storage
        $storagePath = __DIR__ . '/../../storage';
        if (is_writable($storagePath)) {
            $checks['storage'] = 'ok';
        } else {
            $status = 'error';
            $checks['storage'] = 'failed: not writable';
        }
        
        $checks['timestamp'] = time();

        $this->json([
            'status' => $status,
            'checks' => $checks
        ], $status === 'ok' ? 200 : 500);
    }
}
