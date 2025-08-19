<?php
namespace App\Core;

use PDO;
use PDOException;

class App {
    protected static $db;

    public static function db() {
        if (!self::$db) {
            try {
                self::$db = new PDO(
                    "mysql:host=".DB_HOST.";dbname=".DB_NAME,
                    DB_USER,
                    DB_PASS,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch(PDOException $e) {
                die("DB Connection failed: ".$e->getMessage());
            }
        }
        return self::$db;
    }
}
