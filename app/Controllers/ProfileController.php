<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Session;
use App\Core\Database;
use App\Services\UploadService;

class ProfileController extends BaseController
{
    public function index()
    {
        $userId = Session::get('user_id');
        $db = Database::getInstance();
        
        $stmt = $db->query("SELECT * FROM users WHERE id = ?", [$userId]);
        $user = $stmt->fetch();
        
        // Fetch Subscription Info
        $accStmt = $db->query("SELECT plan, subscription_status, next_billing_date FROM accounts WHERE id = ?", [$user['account_id']]);
        $account = $accStmt->fetch();

        // Pass success message if any
        $success = Session::get('flash_success');
        Session::remove('flash_success'); // Flash implementation manual

        $this->view('profile/index', [
            'user' => $user, 
            'account' => $account,
            'success' => $success
        ]);
    }

    public function update()
    {
        $userId = Session::get('user_id');
        $fullname = $_POST['fullname'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $language = $_POST['language'] ?? 'en';
        $timezone = $_POST['timezone'] ?? 'UTC';
        $notify_news = isset($_POST['notify_news']) ? 1 : 0;
        $notify_marketing = isset($_POST['notify_marketing']) ? 1 : 0;
        $notify_goals = isset($_POST['notify_goals']) ? 1 : 0;
        
        // Avatar Upload
        $avatarPath = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            try {
                $uploadService = new UploadService();
                // Save to public/uploads/avatars so it's accessible
                // Our UploadService saves to a dir. Let's assume we mapped storage/uploads to public or serve via PHP.
                // For simplicity in this zero-dep setup, let's save to public/uploads/avatars
                $webPath = 'public/uploads/avatars';
                $destDir = __DIR__ . '/../../' . $webPath;
                
                $filename = $uploadService->upload($_FILES['avatar'], $destDir);
                $avatarPath = '/uploads/avatars/' . $filename;
            } catch (\Exception $e) {
                error_log("Avatar Upload Error: " . $e->getMessage());
            }
        }

        $sql = "UPDATE users SET fullname = ?, phone = ?, bio = ?, language = ?, timezone = ?, notify_news = ?, notify_marketing = ?, notify_goals = ?";
        $params = [$fullname, $phone, $bio, $language, $timezone, $notify_news, $notify_marketing, $notify_goals];
        
        if ($avatarPath) {
            $sql .= ", avatar = ?";
            $params[] = $avatarPath;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $userId;

        $db = Database::getInstance();
        $db->query($sql, $params);

        Session::set('flash_success', 'Profile updated successfully.');
        header('Location: /profile');
        exit;
    }
    public function updatePassword()
    {
        $userId = Session::get('user_id');
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            Session::set('flash_error', 'New passwords do not match.');
            header('Location: /profile');
            exit;
        }

        $db = Database::getInstance();
        $stmt = $db->query("SELECT password_hash FROM users WHERE id = ?", [$userId]);
        $user = $stmt->fetch();

        if (!password_verify($currentPassword, $user['password_hash'])) {
            Session::set('flash_error', 'Current password is incorrect.');
            header('Location: /profile');
            exit;
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $db->query("UPDATE users SET password_hash = ? WHERE id = ?", [$hashed, $userId]);

        Session::set('flash_success', 'Password updated successfully.');
        header('Location: /profile');
        exit;
    }

    public function requestPlan()
    {
        $userId = Session::get('user_id');
        $user = user();
        $ownerEmail = env('ADMIN_OWNER');
        $messageContent = $_POST['message'] ?? 'No message provided.';

        if ($ownerEmail) {
            $subject = "Custom Plan Request from " . $user['email'];
            $body = "<h1>Plan Request</h1><p>User: " . $user['email'] . "</p><p>Message: $messageContent</p>";
            \App\Services\EmailService::send($ownerEmail, $subject, $body);
            Session::set('flash_success', 'Your request has been sent to the administrator.');
        } else {
            Session::set('flash_error', 'Administrator email not configured.');
        }

        header('Location: /profile');
        exit;
    }
}
