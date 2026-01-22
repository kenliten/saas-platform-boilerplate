<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Session;
use App\Core\Database;

class NotificationSenderController extends BaseController
{
    private function dieIfNotAdmin()
    {
        if (Session::get('role') !== 'admin') {
            http_response_code(403);
            die('Unauthorized');
        }
    }

    public function index()
    {
        $this->dieIfNotAdmin();

        $db = Database::getInstance();
        $announcements = $db->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 20")->fetchAll();

        $this->view('admin/notifications/index', ['announcements' => $announcements]);
    }

    public function store()
    {
        $this->dieIfNotAdmin();

        $type = $_POST['type'] ?? 'news';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if (!empty($subject) && !empty($message)) {
            $db = Database::getInstance();
            $db->query("INSERT INTO announcements (type, subject, message) VALUES (?, ?, ?)", [$type, $subject, $message]);
            Session::set('flash_success', 'Notification queued for delivery.');
        } else {
            Session::set('flash_error', 'Subject and Message are required.');
        }

        header('Location: /admin/notifications');
        exit;
    }
}
