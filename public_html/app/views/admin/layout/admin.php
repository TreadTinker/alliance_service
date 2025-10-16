<?php
// Стартуем сессию
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
$pageTitle = $pageTitle ?? 'Админ панель';
$currentSection = $currentSection ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Alliance Service</title>
    <link rel="stylesheet" href="/app/views/admin/assets/css/admin.css">
</head>
<body class="admin-layout">
    <?php include __DIR__ . '/../components/layout/header.php'; ?>
    
    <div class="admin-container">
        <?php include __DIR__ . '/../components/layout/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="admin-content">
                <?= $content ?>
            </div>
        </main>
    </div>
    
    <?php include __DIR__ . '/../components/layout/footer.php'; ?>
    
    <script src="/app/views/admin/assets/js/admin.js"></script>
</body>
</html>