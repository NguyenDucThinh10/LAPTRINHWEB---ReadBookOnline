<?php
namespace App\Core;

class Controller
{
    
    protected function view($path, $data = [])
    {
        extract($data);
        $file = __DIR__ . '/../Views/' . $path . '.php';
        if (file_exists($file)) {
            require $file;
            exit; // 🔥 Dừng luồng xử lý tại đây
        } else {
            echo "❌ Không tìm thấy view: " . htmlspecialchars($path);
            exit;
        }
    }
}