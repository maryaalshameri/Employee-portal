<?php
namespace app\core;

class Auth {
    private $db;
    
public function __construct() {
    $this->db = Database::getInstance()->getConnection(); // تأكد من وجود هذه السطور
}
    
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        
        return false;
    }
    
    public function logout() {
        session_destroy();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function hasPermission($requiredRole) {
        if (!$this->isLoggedIn()) return false;
        return $_SESSION['user_role'] === $requiredRole;
    }
    
    public function getUser() {
        if (!$this->isLoggedIn()) return null;
        
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


         public static function check() {
        if (!isset($_SESSION['user'])) {
            header("Location: /employee-portal/public/login");
            exit;
        }
    }
}