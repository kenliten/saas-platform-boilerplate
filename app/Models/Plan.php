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
}
