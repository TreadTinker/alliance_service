<?php

class Auth
{
    public function login($user)
    {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'first_name' => $user['first_name'] ?? '',
            'last_name' => $user['last_name'] ?? ''
        ];
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    public function getUser()
    {
        return $_SESSION['user'] ?? null;
    }

    public function isAdmin()
    {
        return ($_SESSION['user']['role'] ?? '') === 'admin';
    }

    public function isModerator()
    {
        $role = $_SESSION['user']['role'] ?? '';
        return $role === 'moderator' || $role === 'admin';
    }

    public function isUser()
    {
        return ($_SESSION['user']['role'] ?? '') === 'user';
    }

    public function getUserId()
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public function getUserEmail()
    {
        return $_SESSION['user']['email'] ?? null;
    }

    public function getUserRole()
    {
        return $_SESSION['user']['role'] ?? null;
    }
}