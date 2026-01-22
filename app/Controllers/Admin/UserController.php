<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Database;
use App\Models\User;
use App\Services\EmailService;

class UserController extends BaseController
{
    private $model = new User();

    public function index()
    {
        $this->dieIfNotAdmin();

        $db = Database::getInstance();
        $users = $db->query("
            SELECT u.*, a.plan, a.subscription_status 
            FROM users u 
            JOIN accounts a ON u.account_id = a.id 
            ORDER BY u.created_at DESC
        ")->fetchAll();

        $this->view('admin/users/index', ['users' => $users], 'admin');
    }

    public function toggleStatus()
    {
        $this->dieIfNotAdmin();

        $id = $_POST['id'];

        // Fetch current status
        $user = $this->model->find($id);

        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            $this->model->update($id, [
                'is_active' => $newStatus
            ]);
        }

        header('Location: /admin/users');
        exit;
    }

    public function invite()
    {
        $this->dieIfNotAdmin();

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
        $this->dieIfNotAdmin();

        $userId = $_POST['user_id'];

        // Find user account
        $user = $this->model->find($userId);

        if ($user) {
            $this->model->update($user['id'], [
                'is_active' => 1,
                'account_id' => $user['account_id']
            ]);
        }

        header('Location: /admin/users');
        exit;
    }
}