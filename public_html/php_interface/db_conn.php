<?php

/**
 * DB
 */
class DB
{
    private static $instance = null;
    private $connection;

    private array $config = [
        'host' => 'localhost',
        'dbname' => 'ccadmy1h_al_demo',
        'username' => 'ccadmy1h_al_demo',
        'password' => 'TvqoW83gq*sx',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false
        ]
    ];
    
    /**
     * __construct
     *
     * @return void
     */
    private function __construct()
    {
        try {
            $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
            $this->connection = new PDO($dsn, $this->config['username'], $this->config['password']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Ошибка подключения к базе данных");
        }
    }
    
    /**
     * getInstance
     *
     * @return void
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Выполнить произвольный SQL запрос
     */
    public function query(string $sql, array $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage() . " [SQL: $sql]");
            throw new Exception("Ошибка выполнения запроса");
        }
    }

    /**
     * SELECT - получить все записи
     */
    public function select(string $table, array $conditions = [], string $fields = '*', string $order = '', int $limit = 0, int $offset = 0)
    {
        $where = '';
        $params = [];
        
        if (!empty($conditions)) {
            $where = ' WHERE ' . implode(' AND ', array_map(function($field) {
                return "$field = ?";
            }, array_keys($conditions)));
            $params = array_values($conditions);
        }

        $sql = "SELECT $fields FROM $table $where";
        
        if (!empty($order)) {
            $sql .= " ORDER BY $order";
        }
        
        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset > 0) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }

        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * SELECT - получить одну запись
     */
    public function selectOne(string $table, array $conditions = [], string $fields = '*', string $order = '')
    {
        $result = $this->select($table, $conditions, $fields, $order, 1);
        return $result[0] ?? null;
    }

    /**
     * INSERT - добавить запись
     */
    public function insert(string $table, array $data)
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $this->query($sql, array_values($data));
        
        return $this->connection->lastInsertId();
    }

    /**
     * UPDATE - обновить записи
     */
    public function update(string $table, array $data, array $conditions)
    {
        $set = implode(', ', array_map(function($field) {
            return "$field = ?";
        }, array_keys($data)));
        
        $where = implode(' AND ', array_map(function($field) {
            return "$field = ?";
        }, array_keys($conditions)));
        
        $sql = "UPDATE $table SET $set WHERE $where";
        $params = array_merge(array_values($data), array_values($conditions));
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * DELETE - удалить записи
     */
    public function delete(string $table, array $conditions)
    {
        $where = implode(' AND ', array_map(function($field) {
            return "$field = ?";
        }, array_keys($conditions)));
        
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->query($sql, array_values($conditions));
        
        return $stmt->rowCount();
    }

    /**
     * COUNT - подсчитать количество записей
     */
    public function count(string $table, array $conditions = [])
    {
        $result = $this->selectOne($table, $conditions, 'COUNT(*) as count');
        return (int)($result['count'] ?? 0);
    }

    /**
     * EXISTS - проверить существование записи
     */
    public function exists(string $table, array $conditions)
    {
        return $this->count($table, $conditions) > 0;
    }

    
    /**
     * transaction
     *
     * @param  mixed $callback
     * @return void
     */
    public function transaction(callable $callback)
    {
        try {
            $this->connection->beginTransaction();
            $result = $callback($this);
            $this->connection->commit();
            return $result;
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

        
    /**
     * lastInsertId
     *
     * @return void
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * getConnection
     *
     * @return void
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
