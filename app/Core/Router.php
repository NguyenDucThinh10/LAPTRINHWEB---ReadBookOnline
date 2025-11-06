<?php
// File: app/Core/Router.php
namespace App\Core;

class Router
{
    protected static $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function get($uri, $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post($uri, $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    public static function dispatch($uri, $method)
    {
        // Loại bỏ query string (ví dụ: ?param=1) khỏi URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        if (isset(self::$routes[$method][$uri])) {
            $action = self::$routes[$method][$uri];

            // Tách controller và method từ chuỗi 'Controller@method'
            list($controller, $methodName) = explode('@', $action);
            
            // Xây dựng tên class đầy đủ
            $controllerClassName = 'App\\Controllers\\' . $controller;

            if (class_exists($controllerClassName)) {
                $controllerInstance = new $controllerClassName();

                if (method_exists($controllerInstance, $methodName)) {
                    $controllerInstance->$methodName();
                    return;
                }
            }
        }

        // Nếu không tìm thấy route
        http_response_code(404);
        echo "404 Not Found - Trang bạn tìm không tồn tại.";
        exit;
    }
}