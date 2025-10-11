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
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Сервис управления данными</title>
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