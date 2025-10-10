<?php

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $controller): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        foreach ($this->routes as $route) {
            if ($this->match($route, $method, $uri)) {
                [$controllerClass, $action] = $route['controller'];
                
                // Автозагрузка контроллера
                $controllerFile = __DIR__ . "/../controllers/$controllerClass.php";
                if (!file_exists($controllerFile)) {
                    throw new Exception("Controller not found: $controllerClass");
                }
                
                require_once $controllerFile;
                
                if (!class_exists($controllerClass)) {
                    throw new Exception("Class not found: $controllerClass");
                }
                
                $controller = new $controllerClass();
                
                if (!method_exists($controller, $action)) {
                    throw new Exception("Method not found: $action in $controllerClass");
                }
                
                $controller->$action();
                return;
            }
        }

        // 404
        http_response_code(404);
        echo "Page not found";
    }

    private function match(array $route, string $method, string $uri): bool
    {
        if ($route['method'] !== $method) {
            return false;
        }

        $pattern = $this->convertToRegex($route['path']);
        return preg_match($pattern, $uri) === 1;
    }

    private function convertToRegex(string $path): string
    {
        return '#^' . preg_replace('/\{[^}]+\}/', '([^/]+)', $path) . '$#';
    }
}