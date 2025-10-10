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

// Убираем /public из пути, если есть
$path = preg_replace('#^/public#', '', $path);

// Для отладки
error_log("Request: $method $path");

// // Маршрутизация
// switch ($path) {
//     case '':
//     case '/':
//         $controller = new PublicController();
//         $controller->index();
//         break;
        
//     case '/login':
//         if ($method === 'GET') {
//             $controller = new AuthController();
//             $controller->login();
//         } elseif ($method === 'POST') {
//             $controller = new AuthController();
//             $controller->login();
//         }
//         break;
        
//     case '/register':
//         if ($method === 'GET') {
//             $controller = new AuthController();
//             $controller->register();
//         } elseif ($method === 'POST') {
//             $controller = new AuthController();
//             $controller->register();
//         }
//         break;
        
//     case '/logout':
//         $controller = new AuthController();
//         $controller->logout();
//         break;
        
//     case '/admin':
//         $controller = new AdminController();
//         $controller->dashboard();
//         break;
//     case '/admin/candidates':
//         $controller = new AdminCandidatesController();
//         $controller->index();
//         break;

//     case '/admin/candidates/(.+)':
//         $courierId = $matches[1];
//         $controller = new AdminCandidatesController();
//         $controller->show($courierId);
//         break;
//     // Заглушки для других админ-страниц
//     case '/admin/users':
//     case '/admin/files':
//     case '/admin/tracking':
//     case '/admin/settings':
//         $controller = new AdminController();
//         $controller->dashboard(); // Показываем ту же панель
//         break;
        
//     default:
//         http_response_code(404);
//         echo "Страница не найдена: " . htmlspecialchars($path);
//         break;
// }
$routes = [
    '#^/$#' => ['PublicController', 'index'],
    '#^/login$#' => ['AuthController', 'login'],
    '#^/register$#' => ['AuthController', 'register'],
    '#^/logout$#' => ['AuthController', 'logout'],
    '#^/admin$#' => ['AdminController', 'dashboard'],
    '#^/admin/candidates$#' => ['AdminCandidatesController', 'index'],
    '#^/admin/candidates/([^/]+)$#' => ['AdminCandidatesController', 'show'],
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