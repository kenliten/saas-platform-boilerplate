<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Session;
use App\Models\Notification;

class NotificationController extends BaseController
{
    public function index()
    {
        $userId = Session::get('user_id');
        $notifModel = new Notification();
        
        // Fetch all (read and unread) - simplistic approach
        // We might want pagination in a real app
        $sql = "SELECT * FROM notifications WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC LIMIT 50";
        $stmt = \App\Core\Database::getConnection()->prepare($sql);
        $stmt->execute([$userId]);
        $notifications = $stmt->fetchAll();

        // Mark all as read when visiting the center (simple approach) or let user click.
        // Let's just list them.
        
        return $this->view('notifications/index', ['notifications' => $notifications]);
    }
}
