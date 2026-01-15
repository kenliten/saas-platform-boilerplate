<?php

use App\Core\Session;
use App\Core\Database;

if (!function_exists('is_authenticated')) {
    function is_authenticated()
    {
        return Session::has('user_id');
    }
}

if (!function_exists('user_id')) {
    function user_id()
    {
        return Session::get('user_id');
    }
}

if (!function_exists('user_role')) {
    function user_role()
    {
        return Session::get('role') ?? 'guest';
    }
}

if (!function_exists('user')) {
    function user()
    {
        static $user = null;
        if ($user)
            return $user;

        $id = user_id();
        if (!$id)
            return null;

        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM users WHERE id = ?", [$id]);
        $user = $stmt->fetch();
        return $user;
    }
}

if (!function_exists('user_payment_status')) {
    function user_payment_status()
    {
        static $plan = null;
        if ($plan)
            return $plan;

        $user = user();
        if (!$user)
            return 'free'; // Default or none

        $db = Database::getConnection();
        // Assuming account_id is on user, and accounts table has 'plan' column (slug)
        $stmt = $db->query("SELECT plan FROM accounts WHERE id = ?", [$user['account_id']]);
        $result = $stmt->fetch();

        $plan = $result['plan'] ?? 'free';
        return $plan;
    }
}
