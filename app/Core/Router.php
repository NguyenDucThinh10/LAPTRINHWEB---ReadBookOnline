<?php
namespace App\Core;

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

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];

            if (is_callable($callback)) {
                // ✅ Thêm dòng này: in ra nội dung được return từ Controller
                $result = call_user_func($callback);
                if (is_string($result)) {
                    echo $result;
                }
            } else {
                echo "Route found, but callback is not callable.";
            }
        } else {
            http_response_code(404);
            echo "404 - Page Not Found";
        }
    }
}
