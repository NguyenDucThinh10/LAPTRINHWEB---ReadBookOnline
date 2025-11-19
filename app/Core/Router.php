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
   
        
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        
       


        // LOGIC CŨ (ĐƯỢC GIỮ NGUYÊN)
        // Xử lý các route động (ví dụ: /book/detail/{id})
        foreach (self::$routes[$method] as $route => $action) {
            // Chuyển đổi route có tham số thành regex
            $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([a-zA-Z0-9_]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            // So khớp URI (đã được làm sạch) với pattern
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $params = $matches; 

                list($controller, $methodName) = explode('@', $action);
                $controllerClassName = 'App\\Controllers\\' . $controller;
                if (class_exists($controllerClassName)) {
                    $controllerInstance = new $controllerClassName();
                    if (method_exists($controllerInstance, $methodName)) {
                        call_user_func_array([$controllerInstance, $methodName], $params);
                        return;
                    }
                }
            }
        }

        // Nếu không có route nào khớp
        http_response_code(404);
        echo "404 Not Found - Route '{$uri}' không được định nghĩa.";
        exit;
    }
}