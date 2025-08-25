<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][] = ['uri'=>$uri, 'action'=>$action];
    }

    public function post($uri, $action) {
        $this->routes['POST'][] = ['uri'=>$uri, 'action'=>$action];
    }

    public function dispatch($uri, $requestMethod) {
        $uri = trim($uri, '/');

        if (!isset($this->routes[$requestMethod])) {
            http_response_code(404);
            echo "404 - Page not found";
            return;
        }

        foreach ($this->routes[$requestMethod] as $route) {
            $routeUri = trim($route['uri'], '/');

            // تحويل {param} إلى regex
            $pattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $routeUri);
            $pattern = "#^$pattern$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // إزالة كامل النص المطابق

                [$controller, $method] = explode('@', $route['action']);
                $controller = "App\\Controllers\\$controller";
                (new $controller)->$method(...$matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page not found";
    }
}
