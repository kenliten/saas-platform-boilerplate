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

    public function findById($id)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")");
        $stmt->execute($values);
    }

    public function update($id, $data)
    {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');
        $stmt = $this->db->prepare("UPDATE {$this->table} SET " . implode(' = ?, ', $fields) . " = ? WHERE id = ?");
        $stmt->execute(array_merge($values, [$id]));
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
    }
}
