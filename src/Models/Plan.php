<?php

namespace App\Models;

use App\Core\BaseModel;

class Plan extends BaseModel
{
    protected $table = 'plans';

    public function findBySlug($slug)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE slug = ?", [$slug]);
        return $stmt->fetch();
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

    public function findByName($name)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE name = ?", [$name]);
        return $stmt->fetch();
    }
}
