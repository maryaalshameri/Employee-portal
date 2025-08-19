<?php
namespace app\core;

class Controller {
    protected function view($view, $data = []) {
        // استخراج البيانات إلى متغيرات يمكن استخدامها في العرض
        extract($data);
        
        // تحديد مسار ملف العرض
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        // التحقق من وجود ملف العرض
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // في حالة عدم وجود الملف، عرض خطأ 404
            http_response_code(404);
            echo "View file not found: " . $view;
        }
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}