<?php

namespace App\Core;

abstract class BaseModel
{
    protected $table;
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public function findByColumn($column, $value)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE $column = ?", [$value]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        return $this->db->lastInsertId();
    }
}
