<?php

class PublicController extends Controller
{
    private $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function index()
    {
        $message = '';
        
        // Проверяем параметры URL для сообщений
        if (isset($_GET['error']) && $_GET['error'] === 'access_denied') {
            $message = 'Доступ запрещен. У вас недостаточно прав для просмотра этой страницы.';
        }
        
        if (isset($_GET['welcome']) && $_GET['welcome'] === '1') {
            $message = 'Добро пожаловать! Регистрация прошла успешно.';
        }

        // Если пользователь уже авторизован - показываем персонализированную страницу
        if ($this->auth->isLoggedIn()) {
            $user = $this->auth->getUser();
            $isAdmin = $this->auth->isAdmin();
            $isModerator = $this->auth->isModerator();
            
            $this->render('public/dashboard', [
                'user' => $user,
                'message' => $message,
                'isAdmin' => $isAdmin,
                'isModerator' => $isModerator
            ]);
        } else {
            // Для неавторизованных - обычная главная страница
            $this->render('public/home', [
                'message' => $message
            ]);
        }
    }
}