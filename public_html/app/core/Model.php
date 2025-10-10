<?php

abstract class Model
{
    protected DB $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function find(int $id): ?array
    {
        $result = $this->db->select($this->table, [$this->primaryKey => $id]);
        return $result[0] ?? null;
    }

    public function all(): array
    {
        return $this->db->select($this->table);
    }

    public function where(array $conditions): array
    {
        return $this->db->select($this->table, $conditions);
    }

    public function create(array $data): bool
    {
        return $this->db->insert($this->table, $data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->update($this->table, $data, [$this->primaryKey => $id]);
    }

    public function delete(int $id): bool
    {
        return $this->db->delete($this->table, [$this->primaryKey => $id]);
    }

    public function count(array $conditions = []): int
    {
        $result = $this->db->select($this->table, $conditions, 'COUNT(*) as count');
        return (int)($result[0]['count'] ?? 0);
    }
}