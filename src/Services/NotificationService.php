<?php

namespace App\Services;

use App\Core\Database;
use App\Models\Notification;

class NotificationService
{
    public static function send($userId, $message, $type = 'info')
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $type, $message]);
    }

    public static function broadcast($message, $type = 'info')
    {
        $db = Database::getConnection();
        // Null user_id means global
        $stmt = $db->prepare("INSERT INTO notifications (user_id, type, message) VALUES (NULL, ?, ?)");
        return $stmt->execute([$type, $message]);
    }
}
