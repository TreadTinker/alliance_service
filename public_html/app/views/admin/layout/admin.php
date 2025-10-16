<?php
// Стартуем сессию
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
$pageTitle = $pageTitle ?? 'Админ панель';
$currentSection = $currentSection ?? 'dashboard';

// Функция для проверки активного пункта меню
function isActiveSection($section) {
    global $currentSection;
    print_r($currentSection);
    print_r($section);
    return $currentSection === $section ? 'active bg-gradient-dark text-white' : 'text-dark';
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Alliance Service</title>
    <!-- Fonts and Icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">
    <!-- Local Style -->
    <link rel="stylesheet" href="/app/views/admin/assets/css/admin.css">
    <link rel="stylesheet" href="/app/views/admin/assets/css/nucleo-svg.css">
    <link rel="stylesheet" href="/app/views/admin/assets/css/nucleo-icons.css">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body class="g-sidenav-show  bg-gray-100">
    <?php include __DIR__ . '/../components/menu/sidebar/template.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps ps--active-x ps--active-y">
        <?php include __DIR__ . '/../components/menu/top_menu/template.php'; ?>
        <div class="container-fluid py-2">
            <?= $content ?>
        </div>
        <?php include __DIR__ . '/footer.php'; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="/app/views/admin/assets/js/core/bootstrap.min.js"></script>
    <script src="/app/views/admin/assets/js/core/popper.min.js"></script>
    <script src="/app/views/admin/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/app/views/admin/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/app/views/admin/assets/js/admin.js"></script>
</body>

</html>