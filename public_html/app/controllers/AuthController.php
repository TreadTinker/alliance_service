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
        // Если уже авторизован - редирект в зависимости от роли
        if ($this->auth->isLoggedIn()) {
            $this->redirectBasedOnRole();
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
                    $this->redirectBasedOnRole();
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
        // Если уже авторизован - редирект в зависимости от роли
        if ($this->auth->isLoggedIn()) {
            $this->redirectBasedOnRole();
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

            // Валидация
            if (empty($formData['email'])) {
                $errors['email'] = 'Email обязателен для заполнения';
            } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Введите корректный email';
            } elseif ($this->userModel->userExists($formData['email'])) {
                $errors['email'] = 'Пользователь с таким email уже существует';
            }

            if (empty($formData['first_name'])) {
                $errors['first_name'] = 'Имя обязательно для заполнения';
            } elseif (strlen($formData['first_name']) < 2) {
                $errors['first_name'] = 'Имя должно содержать минимум 2 символа';
            }

            if (empty($password)) {
                $errors['password'] = 'Пароль обязателен для заполнения';
            } elseif (strlen($password) < 6) {
                $errors['password'] = 'Пароль должен содержать минимум 6 символов';
            }

            if ($password !== $confirm_password) {
                $errors['confirm_password'] = 'Пароли не совпадают';
            }

            // Если ошибок нет - создаем пользователя
            if (empty($errors)) {
                $userId = $this->userModel->createUser(
                    $formData['email'],
                    $password,
                    'user', // роль по умолчанию
                    $formData['first_name'],
                    $formData['last_name'],
                    $formData['phone']
                );

                if ($userId) {
                    // Автоматически логиним пользователя после регистрации
                    $user = $this->userModel->findByEmail($formData['email']);
                    $this->auth->login($user);
                    
                    // После регистрации всегда редирект на главную
                    $this->redirect('/?welcome=1');
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

    /**
     * Редирект в зависимости от роли пользователя
     */
    private function redirectBasedOnRole()
    {
        if ($this->auth->isAdmin() || $this->auth->isModerator()) {
            $this->redirect('/admin');
        } else {
            $this->redirect('/');
        }
    }
}