<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الموظفين - المدير</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">لوحة تحكم المدير</h1>
            <div class="flex items-center space-x-4">
                <span>مرحبًا <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                <a href="/employee-portal/public/logout" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded">
                    تسجيل الخروج
                </a>
            </div>
        </div>
    </nav>

    <div class="flex">


        <!-- في dashboard-layout.php -->
<ul class="space-y-2">
    <li><a href="/employee-portal/public/manager/dashboard" class="block p-2 hover:bg-blue-100 rounded">الرئيسية</a></li>
    <li><a href="/employee-portal/public/manager/profile" class="block p-2 hover:bg-blue-100 rounded">الملف الشخصي</a></li>

    <li><a href="/employee-portal/public/manager/employees" class="block p-2 hover:bg-blue-100 rounded">الموظفين</a></li>
    <li><a href="/employee-portal/public/manager/leaves" class="block p-2 hover:bg-blue-100 rounded">الإجازات</a></li>
    <li><a href="/employee-portal/public/manager/salaries" class="block p-2 hover:bg-blue-100 rounded">الرواتب</a></li>
    <li><a href="/employee-portal/public/manager/tasks" class="block p-2 hover:bg-blue-100 rounded">المهام</a></li>
    <li><a href="/employee-portal/public/manager/evaluations" class="block p-2 hover:bg-blue-100 rounded">التقييمات</a></li>
    <!-- روابط جديدة للمدير -->
    <li><a href="/employee-portal/public/manager/leave-request" class="block p-2 hover:bg-blue-100 rounded">طلب إجازة</a></li>
    <!-- <li><a href="/employee-portal/public/employee/salary-request" class="block p-2 hover:bg-blue-100 rounded">طلب راتب</a></li> -->
</ul>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>
</body>
</html>