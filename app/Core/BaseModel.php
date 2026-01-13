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
}
