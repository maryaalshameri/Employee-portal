<?php
// api-test.php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// endpoint للفحص
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/employee-portal/public/api-test') {
    echo json_encode([
        'status' => 'success',
        'message' => 'API is working correctly',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit();
}

// endpoint لتسجيل الدخول (للفحص)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/employee-portal/public/login') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // محاكاة عملية تسجيل الدخول
    $users = [
        'admin@example.com' => [
            'password' => 'admin123',
            'name' => 'أحمد المدير',
            'role' => 'admin'
        ],
        'manager@example.com' => [
            'password' => 'manager123',
            'name' => 'محمد المشرف',
            'role' => 'manager'
        ],
        'employee@example.com' => [
            'password' => 'employee123',
            'name' => 'خالد الموظف',
            'role' => 'employee'
        ]
    ];
    
    if (isset($data['email']) && isset($data['password'])) {
        $email = $data['email'];
        $password = $data['password'];
        
        if (isset($users[$email]) && $users[$email]['password'] === $password) {
            echo json_encode([
                'success' => true,
                'user' => [
                    'name' => $users[$email]['name'],
                    'email' => $email,
                    'role' => $users[$email]['role']
                ],
                'message' => 'تم تسجيل الدخول بنجاح'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'البريد الإلكتروني وكلمة المرور مطلوبان'
        ]);
    }
    
    exit();
}

// إذا لم يتطابق مع أي endpoint
echo json_encode([
    'success' => false,
    'message' => 'Endpoint not found'
]);