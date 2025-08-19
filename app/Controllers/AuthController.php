<?php
namespace App\Controllers;
use App\Core\App;

class AuthController {

    public function login() {
        // عرض صفحة login
        if (!empty($_SESSION['user'])) {
            $role = $_SESSION['user']['role'];
            header("Location: /employee-portal/public/$role");
            exit;
        }
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function doLogin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = App::db()->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute(['email'=>$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /employee-portal/public/{$user['role']}");
        } else {
            $_SESSION['error'] = "بيانات الدخول غير صحيحة";
            header("Location: /employee-portal/public/login");
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /employee-portal/public/login");
    }


}
