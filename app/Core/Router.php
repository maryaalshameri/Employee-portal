<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch($uri, $requestMethod) {
        $uri = trim($uri, '/');

        if (isset($this->routes[$requestMethod][$uri])) {
            $action = $this->routes[$requestMethod][$uri];
            [$controller, $method] = explode('@', $action);

            // إضافة namespace مرة واحدة فقط
            $controller = "App\\Controllers\\$controller";

            // استدعاء الميثود
            (new $controller)->$method();

        } else {
            http_response_code(404);
            echo "404 - Page not found";
        }
    }
}
