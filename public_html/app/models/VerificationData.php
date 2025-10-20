<?php

class VerificationData extends Model
{
    protected string $table = 'verification_data';
    protected string $primaryKey = 'id';

    /**
     * Создать запись (совместим с Model::create)
     */
    public function create(array $data): bool
    {
        return $this->addVerification($data);
    }

    /**
     * Добавить данные сверки
     */
    public function addVerification($data)
    {
        $required = [
            'courier_id',
            'worked_hours',
            'orders_count',
            'total_amount',
            'record_date',
            'period_from',
            'period_to'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Обязательное поле {$field} не заполнено");
            }
        }

        // Проверяем, нет ли уже данных за этот период
        $existing = $this->getByCourierAndPeriod($data['courier_id'], $data['period_from'], $data['period_to']);
        if ($existing) {
            throw new Exception("Данные за указанный период уже существуют");
        }

        // Добавляем временную метку если её нет
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->insert($this->table, $data);
    }

    /**
     * Получить данные сверки по ID курьера и периоду
     */
    public function getByCourierAndPeriod($courierId, $periodFrom, $periodTo)
    {
        return $this->db->selectOne($this->table, [
            'courier_id' => $courierId,
            'period_from' => $periodFrom,
            'period_to' => $periodTo
        ]);
    }

    /**
     * Получить все данные сверки для курьера
     */
    public function getByCourier($courierId, $limit = 0)
    {
        return $this->db->select($this->table, ['courier_id' => $courierId], '*', 'period_from DESC', $limit);
    }

    /**
     * Получить данные за определенный период
     */
    public function getByPeriod($periodFrom, $periodTo)
    {
        return $this->db->select($this->table, [
            'period_from' => $periodFrom,
            'period_to' => $periodTo
        ], '*', 'courier_id ASC');
    }

    /**
     * Получить последние данные сверки
     */
    public function getLatestVerifications($limit = 10)
    {
        return $this->db->select($this->table, [], '*', 'record_date DESC, created_at DESC', $limit);
    }

    /**
     * Обновить данные сверки
     */
    public function updateVerification($id, $data)
    {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    /**
     * Получить статистику по курьерам за период
     */
    public function getPeriodStats($periodFrom, $periodTo)
    {
        $sql = "SELECT 
                COUNT(DISTINCT courier_id) as total_couriers,
                SUM(worked_hours) as total_hours,
                SUM(orders_count) as total_orders,
                SUM(total_amount) as total_amount,
                AVG(worked_hours) as avg_hours_per_courier,
                AVG(orders_count) as avg_orders_per_courier
            FROM {$this->table} 
            WHERE period_from = ? AND period_to = ?";

        return $this->db->query($sql, [$periodFrom, $periodTo])->fetch();
    }

    /**
     * Получить топ курьеров по сумме за период
     */
    public function getTopCouriersByAmount($periodFrom, $periodTo, $limit = 10)
    {
        $sql = "SELECT 
                v.courier_id,
                c.full_name,
                c.city,
                c.manager_name,
                v.total_amount,
                v.worked_hours,
                v.orders_count
            FROM {$this->table} v
            JOIN candidates c ON v.courier_id = c.courier_id
            WHERE v.period_from = ? AND v.period_to = ?
            ORDER BY v.total_amount DESC
            LIMIT ?";

        return $this->db->query($sql, [$periodFrom, $periodTo, $limit])->fetchAll();
    }

    /**
     * Получить общее количество записей сверок
     */
    public function getTotalCount()
    {
        return $this->count();
    }

    /**
     * Получить общую сумму заработка всех курьеров
     */
    public function getTotalEarnings()
    {
        $sql = "SELECT SUM(total_amount) as total FROM {$this->table}";
        $result = $this->db->query($sql)->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Получить последние записи сверок с именами курьеров
     */
    public function getRecentVerificationsWithNames()
    {
        try {
            log_debug('Getting recent verifications with names');

            $sql = "SELECT 
                v.*, 
                c.full_name
            FROM {$this->table} v
            LEFT JOIN candidates c ON v.courier_id = c.courier_id
            ORDER BY v.created_at DESC";

            log_debug('SQL query prepared', ['sql' => $sql]);

            $result = $this->db->query($sql)->fetchAll();

            log_debug('Recent verifications query result', [
                'result_count' => count($result),
                'result_sample' => $result ? array_slice($result, 0, 2) : 'empty'
            ]);

            return $result;
        } catch (Exception $e) {
            log_error('Error in getRecentVerificationsWithNames', [
                'message' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Получить статистику за все время
     */
    public function getOverallStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_verifications,
                SUM(worked_hours) as total_hours,
                SUM(orders_count) as total_orders,
                SUM(total_amount) as total_earnings,
                COUNT(DISTINCT courier_id) as unique_couriers
            FROM {$this->table}";

        return $this->db->query($sql)->fetch();
    }

    /**
     * Получить количество записей за последние N дней
     */
    public function getRecentCount($days = 7)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
        $result = $this->db->query($sql, [$days])->fetch();
        return $result['count'] ?? 0;
    }


    /**
     * Получить все активные записи (для экспорта)
     */
    public function getAll($limit = 0, $offset = 0)
    {
        return $this->db->select($this->table, [], '*', 'id DESC', $limit, $offset);
    }
}
