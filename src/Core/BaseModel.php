<?php

namespace App\Core;

use Exception;

abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $scope_to_tenant = false;
    protected $primary_key = 'id';
    protected $creatable = true;
    protected $updateable = true;
    protected $deletable = true;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // TODO: Scope to tenant
    private function get_tenant_id()
    {
        return null;
        // return $this->scope_to_tenant ? tenant_id() : null;
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if ($tenant_id = $this->get_tenant_id()) {
            $sql .= " WHERE account_id = ?";
            $params[] = $tenant_id;
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primary_key} = ?";
        $params = [$id];

        if ($tenant_id = $this->get_tenant_id()) {
            $sql .= " AND account_id = ?";
            $params[] = $tenant_id;
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch();
    }

    public function findByColumn($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE $column = ?";
        $params = [$value];

        if ($tenant_id = $this->get_tenant_id()) {
            $sql .= " AND account_id = ?";
            $params[] = $tenant_id;
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch();
    }

    public function findAllByColumn($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE $column = ?";
        $params = [$value];

        if ($tenant_id = $this->get_tenant_id()) {
            $sql .= " AND account_id = ?";
            $params[] = $tenant_id;
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function where($conditions, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        $where_clauses = [];

        if ($tenant_id = $this->get_tenant_id()) {
            $where_clauses[] = "account_id = ?";
            $params[] = $tenant_id;
        }

        foreach ($conditions as $column => $value) {
            $where_clauses[] = "$column = ?";
            $params[] = $value;
        }

        if (!empty($where_clauses)) {
            $sql .= " WHERE " . implode(' AND ', $where_clauses);
        }

        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit;
        }
        if ($offset !== null) {
            $sql .= " OFFSET " . (int) $offset;
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        if (!$this->creatable) {
            throw new Exception("This model is not creatable");
        }
        if ($this->scope_to_tenant && !isset($data['account_id'])) {
            $tenant_id = $this->get_tenant_id();
            if ($tenant_id) {
                $data['account_id'] = $tenant_id;
            }
        }

        $db = Database::getConnection();
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $stmt = $db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        return $db->lastInsertId();
    }

    public function update($id, $data)
    {
        if (!$this->updateable) {
            throw new Exception("This model is not updateable");
        }
        $db = Database::getConnection();
        $set_clause = implode(' = ?, ', array_keys($data)) . ' = ?';
        $params = array_values($data);
        $params[] = $id;

        $sql = "UPDATE {$this->table} SET $set_clause WHERE {$this->primary_key} = ?";

        if ($tenant_id = $this->get_tenant_id()) {
            $sql .= " AND account_id = ?";
            $params[] = $tenant_id;
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        if (!$this->deletable) {
            throw new Exception("This model is not deletable");
        }
        $db = Database::getConnection();
        $sql = "DELETE FROM {$this->table} WHERE {$this->primary_key} = ?";
        $params = [$id];

        if ($tenant_id = $this->get_tenant_id()) {
            $sql .= " AND account_id = ?";
            $params[] = $tenant_id;
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        $where_clauses = [];

        if ($tenant_id = $this->get_tenant_id()) {
            $where_clauses[] = "account_id = ?";
            $params[] = $tenant_id;
        }

        foreach ($conditions as $column => $value) {
            $where_clauses[] = "$column = ?";
            $params[] = $value;
        }

        if (!empty($where_clauses)) {
            $sql .= " WHERE " . implode(' AND ', $where_clauses);
        }

        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}
