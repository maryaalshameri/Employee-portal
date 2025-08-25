<?php
namespace App\Models;
use App\Traits\LoggingTrait; 
use App\Core\App;

class User {
     use LoggingTrait;
    public function findByEmail($email) {
        $stmt = App::db()->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
