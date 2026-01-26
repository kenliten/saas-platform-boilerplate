<?php

namespace App\Middlewares;

use App\Core\Database;
use App\Core\Session;

class RateLimitMiddleware
{
    protected $limit = 60; // Requests per minute
    protected $window = 60; // Seconds

    public function handle()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $accountId = Session::get('account_id') ?? 0; // Usage per account if logged in, else per IP effectively?
        // Actually, README says "Rate-limiter per-IP".
        
        // We will misuse the 'rate_limits' table or use a separate logic. 
        // The table schema is (account_id, bucket, tokens, last_refill).
        // If we want per-IP, we might need a different table or use a hash of IP as account_id (hacky).
        // Let's stick to the README "per-IP using simple storage (SQLite file or MySQL table)".
        // I will create a dedicated table for IP rate limits if needed, 
        // OR just use a simple file-based approach for this 'zero dependency' spirit if DB is overkill for every hit.
        // BUT, we have a DB wrapper. Let's look at the schema I created: `rate_limits` table has `account_id` (INT).
        // This suggests the schema was for API rate limits per account.
        // For per-IP protection (DOS), we need a different approach or table.
        // Let's create a quick table if not exists or use a file.
        // I'll use a file-based token bucket for simplicity and speed on "no external packages".
        
        $this->checkRateLimit($ip);
    }

    protected function checkRateLimit($ip)
    {
        $file = __DIR__ . '/../../storage/params/rate_limit_' . md5($ip) . '.json';
        if (!is_dir(dirname($file))) @mkdir(dirname($file), 0777, true);
        
        $data = ['tokens' => $this->limit, 'last_check' => time()];
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
        }

        $now = time();
        $elapsed = $now - $data['last_check'];
        
        // Refill tokens
        // Rate: limit / window
        $refillRate = $this->limit / $this->window;
        $data['tokens'] = min($this->limit, $data['tokens'] + ($elapsed * $refillRate));
        $data['last_check'] = $now;

        if ($data['tokens'] < 1) {
            http_response_code(429);
            header('Retry-After: ' . $this->window);
            die('429 Too Many Requests');
        }

        $data['tokens']--;
        file_put_contents($file, json_encode($data));
    }
}
