<?php
namespace App\Controllers;

use App\Core\Auth;

class BaseController
{
    protected function render($viewPath, $data = [], $layout = null)
    {
        // حماية: نتأكد أن المستخدم مسجل دخول
        Auth::check();

        // تحديد الـ layout تلقائياً حسب دور المستخدم
        if ($layout === null) {
            $role = $_SESSION['user']['role'] ?? 'employee';
            $layout = $role . '/dashboard.php';
        }

        // نجيب بيانات المستخدم من الـ Session
        $userName = $_SESSION['user']['name'] ?? 'مستخدم';
        $userRole = $_SESSION['user']['role'] ?? 'employee';
        
        // نمرر أي بيانات إضافية جت من الكنترولر
        extract($data);

        // نلتقط محتوى الـ view المطلوب
        ob_start();
        require __DIR__ . '/../Views/' . $viewPath;
        $content = ob_get_clean();

        // نعرضه داخل الـ Layout المناسب
        require __DIR__ . '/../Views/' . $layout;
    }

    protected function checkRole($allowedRoles) {
        if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role'], $allowedRoles)) {
            header("Location: /employee-portal/public/login");
            exit;
        }
    }
}