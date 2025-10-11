<?php

abstract class Controller
{
    /**
     * Универсальный метод для рендеринга view с layout
     */
    protected function render($view, $data = [])
    {
        // Извлекаем переменные для использования в view
        extract($data);
        
        // Подключаем шапку
        require_once __DIR__ . '/../views/layout/header.php';

        // Подключаем основной контент
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new Exception("View not found: $view");
        }
        require_once $viewPath;

        // Подключаем подвал
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Редирект на указанный URL
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Возвращает JSON ответ
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Проверяет POST запрос
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Проверяет GET запрос
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}