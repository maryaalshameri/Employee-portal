<?php
namespace App\Models;

use App\Core\App;

class User {
    public function findByEmail($email) {
        $stmt = App::db()->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
