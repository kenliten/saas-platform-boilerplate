<?php

namespace App\Models;

use App\Core\BaseModel;

class Notification extends BaseModel
{
    protected $table = 'notifications';

    public function getUnreadForUser($userId)
    {
        // Get global (user_id IS NULL) + specific user notifications
        // And ensure we don't fetch "read" ones (handling global read state is complex without a pivot, 
        // so for now Global notifications are always "unread" in this simple system or we skip global logic for unread check to avoid complexity)
        // Let's stick to User-Specific notifications for unread count simplicity.

        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
        $stmt = $this->db->query($sql, [$userId]);
        return $stmt->fetchAll();
    }

    public function markAsRead($id, $userId)
    {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE id = ? AND user_id = ?";
        return $this->db->query($sql, [$id, $userId]);
    }
}
