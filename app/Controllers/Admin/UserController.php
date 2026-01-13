<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Session;
use App\Core\Database;

class UserController extends BaseController
{
    public function index()
    {
        // Enforce Admin Role
        if (Session::get('role') !== 'admin') {
            http_response_code(403);
            die('Unauthorized');
        }

        $db = Database::getConnection();
        $users = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
        
        $this->view('admin/users/index', ['users' => $users]);
    }

    public function toggleStatus()
    {
        if (Session::get('role') !== 'admin') {
            http_response_code(403);
            die('Unauthorized');
        }

        $id = $_POST['id'];
        $db = Database::getConnection();
        
        // Fetch current status
        $stmt = $db->prepare("SELECT is_active FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            $update = $db->prepare("UPDATE users SET is_active = ? WHERE id = ?");
            $update->execute([$newStatus, $id]);
        }
        
        header('Location: /admin/users');
        exit;
    }
}
