<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class User extends BaseModel
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE email = ?", [$email]);
        return $stmt->fetch();
    }
}
