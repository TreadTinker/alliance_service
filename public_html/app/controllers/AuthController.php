<?php

class AuthController extends Controller
{
    private $userModel;
    private $auth;

    public function __construct()
    {
        $this->userModel = new User();
        $this->auth = new Auth();
    }

    public function login()
    {
        // Если уже авторизован - редирект
        if ($this->auth->isLoggedIn()) {
            $this->redirect('/admin');
        }

        $error = '';
        $email = '';

        // Обработка формы
        if ($this->isPost()) {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Все поля обязательны для заполнения';
            } else {
                $user = $this->userModel->findByEmail($email);
                
                if ($user && password_verify($password, $user['password'])) {
                    $this->auth->login($user);
                    $this->redirect('/admin');
                } else {
                    $error = 'Неверный email или пароль';
                }
            }
        }

        // Показываем форму через view
        $this->render('auth/login', [
            'error' => $error,
            'email' => $email
        ]);
    }

    public function register()
    {
        // Если уже авторизован - редирект
        if ($this->auth->isLoggedIn()) {
            $this->redirect('/admin');
        }

        $errors = [];
        $formData = [
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'phone' => ''
        ];

        // Обработка формы регистрации
        if ($this->isPost()) {
            $formData = [
                'email' => trim($_POST['email'] ?? ''),
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? '')
            ];
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Валидация (остается без изменений)
            // ... существующий код валидации ...

            // Если ошибок нет - создаем пользователя
            if (empty($errors)) {
                $userId = $this->userModel->createUser(
                    $formData['email'],
                    $password,
                    'user',
                    $formData['first_name'],
                    $formData['last_name'],
                    $formData['phone']
                );

                if ($userId) {
                    $user = $this->userModel->findByEmail($formData['email']);
                    $this->auth->login($user);
                    $this->redirect('/admin?welcome=1');
                } else {
                    $errors['general'] = 'Ошибка при создании пользователя. Попробуйте позже.';
                }
            }
        }

        // Показываем форму через view
        $this->render('auth/register', [
            'errors' => $errors,
            'formData' => $formData
        ]);
    }

    public function logout()
    {
        $this->auth->logout();
        $this->redirect('/login');
    }
}