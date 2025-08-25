<?php
namespace App\Controllers;

class HomeController {

    public function index() {
        if (!empty($_SESSION['user'])) {
            $role = $_SESSION['user']['role'];
            header("Location: /employee-portal/public/$role");
        } else {
            header("Location: /employee-portal/public/login");
        }
        exit;
    }

    public function admin() {
        // تحقق: لازم يكون role = admin
        if (empty($_SESSION['user'])) {
            header("Location: /employee-portal/public/login");
            exit;
        }
        if ($_SESSION['user']['role'] !== 'admin') {
            // رجعه لمساره الصحيح
            $role = $_SESSION['user']['role'];
            header("Location: /employee-portal/public/$role");
            exit;
        }

        $userName = $_SESSION['user']['name'] ?? 'مدير';
        $userRole = $_SESSION['user']['role'] ?? 'admin';
        require __DIR__ . '/../Views/admin/dashboard.php';
    }

public function manager() {
    // تحقق: لازم يكون role = manager
    if (empty($_SESSION['user'])) {
        header("Location: /employee-portal/public/login");
        exit;
    }
    if ($_SESSION['user']['role'] !== 'manager') {
        $role = $_SESSION['user']['role'];
        header("Location: /employee-portal/public/$role");
        exit;
    }

    // توجيه إلى dashboard المدير بدلاً من view مباشر
    header("Location: /employee-portal/public/manager/dashboard");
    exit;
}

    public function employee() {
        // تحقق: لازم يكون role = employee
        if (empty($_SESSION['user'])) {
            header("Location: /employee-portal/public/login");
            exit;
        }
        if ($_SESSION['user']['role'] !== 'employee') {
            $role = $_SESSION['user']['role'];
            header("Location: /employee-portal/public/$role");
            exit;
        }

        $userName = $_SESSION['user']['name'] ?? 'موظف';
        $userRole = $_SESSION['user']['role'] ?? 'employee';
        require __DIR__ . '/../Views/employee/dashboard.php';
    }
}
