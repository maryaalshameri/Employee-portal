<?php
namespace App\Traits;

trait LoggingTrait {
    public function log($message){
        $logDir = __DIR__ . '/../../logs'; 
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true); 
        }
        $logFile = $logDir . '/app.log';
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
    }
}