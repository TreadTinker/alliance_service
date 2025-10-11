<?php
// Стартуем сессию если не запущена
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
?>
<header class="admin-header">
    <div class="header-container">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <h1 class="logo">Alliance Service Admin</h1>
        </div>
        
        <div class="header-right">
            <div class="user-menu">
                <span class="user-greeting">Добро пожаловать, <?= htmlspecialchars($user['first_name'] ?? 'Администратор') ?></span>
                <div class="user-dropdown">
                    <a href="/" class="dropdown-item">На сайт</a>
                    <a href="/logout" class="dropdown-item">Выйти</a>
                </div>
            </div>
        </div>
    </div>
</header>