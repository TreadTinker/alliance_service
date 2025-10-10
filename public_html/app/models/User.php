<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    // Старый метод (оставлен для совместимости)
    public function findByEmail($email)
    {
        try {
            $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Новые методы с использованием улучшенного DB класса
    
    public function findByEmailNew($email)
    {
        return $this->db->selectOne('users', ['email' => $email]);
    }

    public function getAllUsers($limit = 0)
    {
        return $this->db->select('users', [], '*', 'created_at DESC', $limit);
    }

    public function createUser($email, $password, $role = 'user', $firstName = '', $lastName = '', $phone = '')
    {
        $data = [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'is_active' => true,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('users', $data);
    }

    public function updateUser($userId, $data)
    {
        return $this->db->update('users', $data, ['id' => $userId]);
    }

    public function deleteUser($userId)
    {
        return $this->db->delete('users', ['id' => $userId]);
    }

    public function getUserCount()
    {
        return $this->db->count('users');
    }

    public function userExists($email)
    {
        return $this->db->exists('users', ['email' => $email]);
    }

    public function getUsersByRole($role, $limit = 10)
    {
        return $this->db->select('users', ['role' => $role], '*', 'created_at DESC', $limit);
    }

    public function activateUser($userId)
    {
        return $this->db->update('users', ['is_active' => true], ['id' => $userId]);
    }

    public function deactivateUser($userId)
    {
        return $this->db->update('users', ['is_active' => false], ['id' => $userId]);
    }

    public function updateLastLogin($userId)
    {
        return $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], ['id' => $userId]);
    }
}