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
        require __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function manager() {
        require __DIR__ . '/../Views/manager/dashboard.php';
    }

    public function employee() {
        require __DIR__ . '/../Views/employee/dashboard.php';
    }
}
