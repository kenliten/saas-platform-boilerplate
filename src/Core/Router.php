<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $globalMiddleware = [];
    protected $lastRoute = null;

    public function globalMiddleware($middleware)
    {
        $this->globalMiddleware[] = $middleware;
        return $this;
    }

    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
        return $this;
    }

    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
        return $this;
    }

    protected function addRoute($method, $path, $callback)
    {
        $this->routes[$method][$path] = [
            'callback' => $callback,
            'middleware' => []
        ];
        $this->lastRoute = ['method' => $method, 'path' => $path];
    }

    public function middleware($middleware)
    {
        if ($this->lastRoute) {
            $this->routes[$this->lastRoute['method']][$this->lastRoute['path']]['middleware'][] = $middleware;
        }
        return $this; // Fluent interface
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $path = trim($path, '/');
        if ($path === '') {
            $path = '/';
        } else {
            $path = '/' . $path;
        }

        $route = $this->routes[$method][$path] ?? false;

        if (!$route) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $callback = $route['callback'];
        $middlewares = array_merge($this->globalMiddleware, $route['middleware']);

        // Execute Middleware
        foreach ($middlewares as $mwClass) {
            $mw = new $mwClass();
            // Middleware handle method should return true to continue, or false/exit if failed
            // Or redirection inside middleware.
            if (method_exists($mw, 'handle')) {
                $mw->handle();
            }
        }

        if (is_array($callback)) {
            $controller = new $callback[0];
            $action = $callback[1];
            return $controller->$action();
        }

        if (is_callable($callback)) {
            return call_user_func($callback);
        }
    }
}
