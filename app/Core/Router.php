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
    foreach (self::$routes[$method] as $route => $action) {
        // Chuyển đổi route có tham số (vd: /book/detail/{id}) thành một biểu thức chính quy (regex)
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([a-zA-Z0-9_]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        // So khớp URI hiện tại với pattern
        if (preg_match($pattern, $uri, $matches)) {
            // Xóa phần tử đầu tiên (là toàn bộ chuỗi URI khớp được)
            array_shift($matches);
            $params = $matches; // Các tham số còn lại (ví dụ: số '3')

            list($controller, $methodName) = explode('@', $action);
            $controllerClassName = 'App\\Controllers\\' . $controller;
            if (class_exists($controllerClassName)) {
                $controllerInstance = new $controllerClassName();
                if (method_exists($controllerInstance, $methodName)) {
                    // Gọi phương thức và truyền các tham số đã bắt được vào
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