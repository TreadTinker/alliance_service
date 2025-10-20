<?php

class ReconciliationFile extends Model
{
    protected string $table = 'reconciliation_files';
    protected string $primaryKey = 'id';

    /**
     * Получить все файлы с пагинацией
     */
    public function getAllWithPagination($page = 1, $perPage = 20, $search = '')
    {
        $offset = ($page - 1) * $perPage;
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = "WHERE file_name LIKE ? OR description LIKE ?";
            $params = ["%{$search}%", "%{$search}%"];
        }

        $sql = "SELECT rf.*, u.first_name, u.last_name 
                FROM {$this->table} rf 
                LEFT JOIN users u ON rf.uploaded_by = u.id 
                {$where} 
                ORDER BY rf.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params = array_merge($params, [$perPage, $offset]);
        return $this->db->query($sql, $params)->fetchAll();
    }

    /**
     * Получить общее количество файлов
     */
    public function getTotalCount($search = '')
    {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = "WHERE file_name LIKE ? OR description LIKE ?";
            $params = ["%{$search}%", "%{$search}%"];
        }

        $sql = "SELECT COUNT(*) as count FROM {$this->table} {$where}";
        $result = $this->db->query($sql, $params)->fetch();
        return $result['count'] ?? 0;
    }

    /**
     * Получить файл с кандидатами
     */
    public function getWithCandidates($fileId)
    {
        // Получаем информацию о файле
        $sql = "SELECT rf.*, u.first_name, u.last_name 
                FROM {$this->table} rf 
                LEFT JOIN users u ON rf.uploaded_by = u.id 
                WHERE rf.id = ?";
        
        $file = $this->db->query($sql, [$fileId])->fetch();
        
        if (!$file) {
            return null;
        }

        // Получаем кандидатов, связанных с этим файлом
        $sql = "SELECT c.* 
                FROM candidates c 
                INNER JOIN file_candidates fc ON c.id = fc.candidate_id 
                WHERE fc.file_id = ? 
                ORDER BY c.full_name";
        
        $file['candidates'] = $this->db->query($sql, [$fileId])->fetchAll();

        return $file;
    }

    /**
     * Создать запись о файле
     */
    public function createFile($data)
    {
        $defaults = [
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $data = array_merge($defaults, $data);
        return $this->db->insert($this->table, $data);
    }

    /**
     * Обновить информацию о файле
     */
    public function updateFile($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    /**
     * Удалить файл и связанные записи
     */
    public function deleteFile($id)
    {
        // Удаляем связи с кандидатами
        $this->db->delete('file_candidates', ['file_id' => $id]);
        
        // Удаляем связанные данные верификации
        $this->db->query("DELETE vd FROM verification_data vd 
                         INNER JOIN file_candidates fc ON vd.courier_id IN (
                             SELECT c.courier_id FROM candidates c 
                             INNER JOIN file_candidates fc ON c.id = fc.candidate_id 
                             WHERE fc.file_id = ?
                         )", [$id]);
        
        // Удаляем файл
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Добавить кандидатов к файлу
     */
    public function addCandidatesToFile($fileId, $candidateIds)
    {
        foreach ($candidateIds as $candidateId) {
            $this->db->insert('file_candidates', [
                'file_id' => $fileId,
                'candidate_id' => $candidateId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Получить ID кандидатов по их courier_id
     */
    public function getCandidateIdsByCourierIds($courierIds)
    {
        if (empty($courierIds)) {
            return [];
        }

        $placeholders = str_repeat('?,', count($courierIds) - 1) . '?';
        $sql = "SELECT id FROM candidates WHERE courier_id IN ({$placeholders})";
        
        $result = $this->db->query($sql, $courierIds)->fetchAll();
        return array_column($result, 'id');
    }

    /**
     * Получить статистику по файлам
     */
    public function getStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_files,
                SUM(file_size) as total_size,
                MAX(created_at) as last_upload,
                SUM(records_count) as total_records
            FROM {$this->table}";
        
        return $this->db->query($sql)->fetch();
    }

    /**
     * Форматировать размер файла
     */
    public function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}