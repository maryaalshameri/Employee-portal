<?php
namespace App\Traits;

namespace App\Traits;

trait LoggingTrait {
    public function log($message, $context = []) {
        $logDir = __DIR__ . '/../../logs'; 
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true); 
        }
        $logFile = $logDir . '/app.log';
        
        $logEntry = date('Y-m-d H:i:s') . " - ";
        
        // إضافة معلومات المستخدم من الجلسة
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $logEntry .= "[User: {$user['id']}|{$user['name']}|{$user['role']}] ";
        }
        
        // إضافة معلومات السياق إذا وجدت
        if (!empty($context)) {
            $logEntry .= "[Context: " . json_encode($context) . "] ";
        }
        
        $logEntry .= $message . "\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}