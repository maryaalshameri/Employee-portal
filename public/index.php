<?php
session_start();

// تحميل البيئة
$env = parse_ini_file(__DIR__ . '/../.env');

define('DB_HOST', $env['DB_HOST']);
define('DB_NAME', $env['DB_NAME']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);

// autoload
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    if (strpos($class, 'App/') === 0) $class = substr($class, 4);
    $file = __DIR__ . '/../app/' . $class . '.php';
    if (file_exists($file)) require $file;
});

require __DIR__ . '/../routes/web.php';

// تسجيل الدخول والخروج
