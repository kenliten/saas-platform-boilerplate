<?php

use App\Core\Session;
use App\Models\Account;
use App\Models\Plan;
use App\Models\User;

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
        $userModel = new User();
        static $user = null;
        if ($user)
            return $user;

        $id = user_id();
        if (!$id)
            return null;

        $user = $userModel->find($id);
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
            return ['plan' => 'free', 'status' => 'inactive'];

        $accountModel = new Account();
        $account = $accountModel->find($user['account_id']);
        $plan = $account['plan'] ?? 'free';
        $status = $account['status'] ?? 'inactive';
        return ['plan' => $plan, 'status' => $status];
    }
}

if (!function_exists('is_pro')) {
    function is_pro()
    {
        static $isPro = null;
        if ($isPro !== null)
            return $isPro;

        $user = user();
        if (!$user)
            return false;

        if ($user['role'] === 'admin')
            return true;

        $accountModel = new Account();
        $result = $accountModel->find($user['account_id']);

        $isPro = (($result['plan'] ?? '') === 'pro' && ($result['subscription_status'] ?? '') === 'active');
        return $isPro;
    }
}
