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
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        // Pass success message if any
        $success = Session::get('flash_success');
        Session::remove('flash_success'); // Flash implementation manual

        $this->view('profile/index', ['user' => $user, 'success' => $success]);
    }

    public function update()
    {
        $userId = Session::get('user_id');
        $fullname = $_POST['fullname'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $bio = $_POST['bio'] ?? '';
        
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
                // Ignore error for now or handle it
            }
        }

        $sql = "UPDATE users SET fullname = ?, phone = ?, bio = ?";
        $params = [$fullname, $phone, $bio];
        
        if ($avatarPath) {
            $sql .= ", avatar = ?";
            $params[] = $avatarPath;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $userId;

        $db = Database::getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

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

        $db = Database::getConnection();
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
}
