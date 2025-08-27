<?php
namespace App\Models;
use App\Traits\LoggingTrait; 
use App\Core\App;

class User {
     use LoggingTrait;
    
    public function findByEmail($email) {
        try {
            $stmt = App::db()->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            $this->log("بحث عن مستخدم بالبريد: $email", [
                'found' => $result ? 'نعم' : 'لا',
                'user_id' => $result['id'] ?? null
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            $this->log("خطأ في البحث عن مستخدم: " . $e->getMessage(), [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}