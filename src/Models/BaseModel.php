<?php

namespace App\Models;

use App\Core\Database;

class BaseModel
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO {$this->table} (" . implode(", ", array_keys($data)) . ") VALUES (" . implode(", ", array_fill(0, count($data), "?")) . ")");
        $stmt->execute(array_values($data));
    }

    public function update($id, $data)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE {$this->table} SET " . implode(", ", array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($data))) . " WHERE id = ?");
        $stmt->execute(array_merge(array_values($data), [$id]));
    }

    public function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
    }
}