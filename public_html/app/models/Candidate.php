<?php

class Candidate extends Model
{
    protected string $table = 'candidates';
    protected string $primaryKey = 'id';

    /**
     * Найти курьера по ID
     */
    public function findByCourierId($courierId)
    {
        return $this->db->selectOne($this->table, ['courier_id' => $courierId]);
    }

    /**
     * Получить всех активных курьеров
     */
    public function getActiveCandidates()
    {
        return $this->db->select($this->table, ['status' => 'active'], '*', 'full_name ASC');
    }

    /**
     * Получить курьеров по менеджеру
     */
    public function getByManager($managerName)
    {
        return $this->db->select($this->table, ['manager_name' => $managerName], '*', 'full_name ASC');
    }

    /**
     * Получить курьеров по городу
     */
    public function getByCity($city)
    {
        return $this->db->select($this->table, ['city' => $city], '*', 'full_name ASC');
    }

    /**
     * Получить курьеров по отделу
     */
    public function getByDepartment($department)
    {
        return $this->db->select($this->table, ['department' => $department], '*', 'full_name ASC');
    }

    /**
     * Поиск курьеров по ФИО
     */
    public function searchByName($searchTerm)
    {
        $sql = "SELECT * FROM {$this->table} WHERE full_name LIKE ? AND status = 'active' ORDER BY full_name ASC";
        return $this->db->query($sql, ["%{$searchTerm}%"])->fetchAll();
    }

    /**
     * Создать нового курьера
     */
    public function createCandidate($data)
    {
        $required = ['courier_id', 'full_name', 'city', 'phone_number', 'manager_name', 'department'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Обязательное поле {$field} не заполнено");
            }
        }

        return $this->db->insert($this->table, $data);
    }

    /**
     * Обновить данные курьера
     */
    public function updateCandidate($courierId, $data)
    {
        return $this->db->update($this->table, $data, ['courier_id' => $courierId]);
    }

    /**
     * Деактивировать курьера
     */
    public function deactivateCandidate($courierId)
    {
        return $this->db->update($this->table, ['status' => 'inactive'], ['courier_id' => $courierId]);
    }

    /**
     * Получить уникальные отделы
     */
    public function getUniqueDepartments()
    {
        $sql = "SELECT DISTINCT department FROM {$this->table} WHERE department IS NOT NULL ORDER BY department";
        $result = $this->db->query($sql)->fetchAll();
        return array_column($result, 'department');
    }

    /**
     * Получить уникальные города
     */
    public function getUniqueCities()
    {
        $sql = "SELECT DISTINCT city FROM {$this->table} WHERE city IS NOT NULL ORDER BY city";
        $result = $this->db->query($sql)->fetchAll();
        return array_column($result, 'city');
    }

    /**
     * Получить кандидатов с пагинацией
     */
    public function getWithPagination($page = 1, $perPage = 20, $filters = [])
    {
        $where = '';
        $params = [];
        
        if (!empty($filters)) {
            $conditions = [];
            foreach ($filters as $field => $value) {
                if (!empty($value)) {
                    $conditions[] = "$field = ?";
                    $params[] = $value;
                }
            }
            if (!empty($conditions)) {
                $where = 'WHERE ' . implode(' AND ', $conditions);
            }
        }

        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table} {$where} ORDER BY full_name ASC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    /**
     * Получить общее количество кандидатов
     */
    public function getTotalCount($filters = [])
    {
        $where = '';
        $params = [];
        
        if (!empty($filters)) {
            $conditions = [];
            foreach ($filters as $field => $value) {
                if (!empty($value)) {
                    $conditions[] = "$field = ?";
                    $params[] = $value;
                }
            }
            if (!empty($conditions)) {
                $where = 'WHERE ' . implode(' AND ', $conditions);
            }
        }

        $sql = "SELECT COUNT(*) as total FROM {$this->table} {$where}";
        $result = $this->db->query($sql, $params)->fetch();
        
        return $result['total'] ?? 0;
    }
}