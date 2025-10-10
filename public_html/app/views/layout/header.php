<?php
// Стартуем сессию в самом начале
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Получаем данные пользователя из сессии
$user = $_SESSION['user'] ?? null;
$isLoggedIn = !empty($user);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCAdmModules - Сервис управления данными</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: white;
        }

        .logo h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-welcome {
            font-size: 14px;
            opacity: 0.9;
        }

        .user-email {
            font-weight: 600;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-align: center;
        }

        .btn-login {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .btn-login:hover {
            background: rgba(255,255,255,0.3);
        }

        .btn-register {
            background: white;
            color: #667eea;
        }

        .btn-register:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }

        .btn-logout {
            background: rgba(255,255,255,0.1);
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.2);
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
            min-height: calc(100vh - 70px);
        }

        .content-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                height: auto;
                padding: 15px 20px;
                gap: 15px;
            }

            .nav {
                width: 100%;
                justify-content: center;
            }

            .user-info {
                flex-direction: column;
                gap: 10px;
            }

            .user-welcome {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="/" class="logo">
                <h1>Alliance Services</h1>
            </a>
            
            <nav class="nav">
                <?php if ($isLoggedIn): ?>
                    <div class="user-info">
                        <div class="user-welcome">
                            Добро пожаловать, <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                        <a href="/logout" class="btn btn-logout">Выйти</a>
                    </div>
                <?php else: ?>
                    <div class="user-info">
                        <a href="/login" class="btn btn-login">Войти</a>
                        <a href="/register" class="btn btn-register">Регистрация</a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="main-container">
        <div class="content-card">