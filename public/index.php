<?php

session_start();

// تحميل البيئة


// autoload
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    if (strpos($class, 'App/') === 0) $class = substr($class, 4);
    $file = __DIR__ . '/../app/' . $class . '.php';
    if (file_exists($file)) require $file;
});

require __DIR__ . '/../routes/web.php';

$router->dispatch($_GET['url'] ?? '', $_SERVER['REQUEST_METHOD']);
// تسجيل الدخول والخروج
