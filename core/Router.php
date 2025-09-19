<?php
class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }
    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function run($requestPath = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $requestPath ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $callback = $this->routes[$method][$path];

        if (is_string($callback)) {
            [$controller, $action] = explode('@', $callback);
            $controllerObj = new $controller;
            call_user_func([$controllerObj, $action]);
        } else {
            call_user_func($callback);
        }
    }
}
