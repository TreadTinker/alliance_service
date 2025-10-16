<?php
// Включаем вывод ошибок для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Стартуем сессию
session_start();

// Определяем корневую директорию проекта
define('ROOT_DIR', dirname(__DIR__));

// Базовая автозагрузка классов
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_DIR . '/app/controllers/',
        ROOT_DIR . '/app/models/',
        ROOT_DIR . '/app/core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Подключаем базу данных
require_once ROOT_DIR . '/php_interface/db_conn.php';

// Простой роутер
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Убираем параметры запроса
$path = parse_url($requestUri, PHP_URL_PATH);

/// Убираем /public из пути, если есть
$path = preg_replace('#^/public#', '', $path);

// Для отладки
error_log("Request: $method $path");

if (preg_match('#^/app/views/admin/assets/#', $path)) {
    $filePath = ROOT_DIR . $path;
    
    if (file_exists($filePath) && is_file($filePath)) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = $mimeTypes[$extension] ?? 'text/plain';
        
        header('Content-Type: ' . $mimeType);
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo "Admin asset not found: " . htmlspecialchars($filePath);
        exit;
    }
}

if (preg_match('#^/assets/#', $path)) {
    $filePath = ROOT_DIR . $path; // Добавляем /public к пути
    
    if (file_exists($filePath) && is_file($filePath)) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = $mimeTypes[$extension] ?? 'text/plain';
        
        header('Content-Type: ' . $mimeType);
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo "File not found: " . htmlspecialchars($filePath);
        exit;
    }
}

// // Маршрутизация
$routes = [
    // Главная страница
    '#^/$#' => ['PublicController', 'index'],
    '#^/tarif$#' => ['PublicController', 'tarif'],
    
    // Аутентификация
    '#^/login$#' => ['AuthController', 'login'],
    '#^/register$#' => ['AuthController', 'register'],
    '#^/logout$#' => ['AuthController', 'logout'],
    
    // Админка - dashboard
    '#^/admin$#' => ['AdminController', 'dashboard'],
    '#^/admin/dashboard$#' => ['AdminController', 'dashboard'],
    '#^/admin/tables$#' => ['AdminController', 'tables'],
    
    // Админка - кандидаты (специфичные маршруты сначала)
    '#^/admin/candidates/([^/]+)/verifications$#' => ['AdminCandidatesController', 'verifications'],
    '#^/admin/candidates/([^/]+)/edit$#' => ['AdminCandidatesController', 'edit'],
    '#^/admin/candidates/([^/]+)$#' => ['AdminCandidatesController', 'show'],
    '#^/admin/candidates/add$#' => ['AdminCandidatesController', 'add'],
    '#^/admin/candidates/export$#' => ['AdminCandidatesController', 'export'],
    '#^/admin/candidates$#' => ['AdminCandidatesController', 'index'],
    
    // Общие админ-маршруты
    '#^/admin/(users|files|tracking|settings)$#' => ['AdminController', 'dashboard'],

];

$matched = false;

foreach ($routes as $pattern => $handler) {
    if (preg_match($pattern, $path, $matches)) {
        $matched = true;
        
        list($controllerClass, $action) = $handler;
        
        // Создаем контроллер
        $controller = new $controllerClass();
        
        // Если есть дополнительные параметры (например, courier_id)
        if (count($matches) > 1) {
            array_shift($matches); // убираем полное совпадение
            call_user_func_array([$controller, $action], $matches);
        } else {
            $controller->$action();
        }
        
        break;
    }
}

if (!$matched) {
    http_response_code(404);
    echo "Страница не найдена: " . htmlspecialchars($path);
}