<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Session;
use App\Core\Database;
use App\Services\EmailService;

class UserController extends BaseController
{
    public function index()
    {
        // Enforce Admin Role
        if (Session::get('role') !== 'admin') {
            http_response_code(403);
            die('Unauthorized');
        }

        $db = Database::getInstance();
        $users = $db->query("
            SELECT u.*, a.plan, a.subscription_status 
            FROM users u 
            JOIN accounts a ON u.account_id = a.id 
            ORDER BY u.created_at DESC
        ")->fetchAll();
        
        $this->view('admin/users/index', ['users' => $users]);
    }

    public function toggleStatus()
    {
        if (Session::get('role') !== 'admin') {
            http_response_code(403);
            die('Unauthorized');
        }

        $id = $_POST['id'];
        $db = Database::getInstance();
        
        // Fetch current status
        $user = $db->query("SELECT is_active FROM users WHERE id = ?", [$id])->fetch();
        
        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            $db->query("UPDATE users SET is_active = ? WHERE id = ?", [$newStatus, $id]);
        }
        
        header('Location: /admin/users');
        exit;
    }

    public function invite()
    {
        if (Session::get('role') !== 'admin') {
            http_response_code(403);
            die('Unauthorized');
        }

        $emailsString = $_POST['emails'] ?? '';
        $emails = explode(',', $emailsString);
        
        foreach ($emails as $email) {
            $email = trim($email);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Send Invite
                $subject = "You are invited to join " . env('APP_NAME');
                $link = env('APP_URL') . "/register?email=" . urlencode($email);
                $message = "<h1>Welcome!</h1><p>You have been invited to join " . env('APP_NAME') . ".</p><p><a href='$link'>Click here to register</a></p>";
                
                EmailService::send($email, $subject, $message);
            }
        }

        header('Location: /admin/users');
        exit;
    }

    public function activateSubscription()
    {
        if (Session::get('role') !== 'admin') {
            http_response_code(403);
            die('Unauthorized');
        }

        $userId = $_POST['user_id'];
        $db = Database::getInstance();
        
        // Find user account
        $user = $db->query("SELECT account_id FROM users WHERE id = ?", [$userId])->fetch();
        
        if ($user) {
            $db->query("UPDATE accounts SET plan = 'pro', subscription_status = 'active', subscription_id = 'MANUAL', next_billing_date = NULL WHERE id = ?", [$user['account_id']]);
        }

        header('Location: /admin/users');
        exit;
    }
}