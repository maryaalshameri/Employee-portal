<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8d0865ff;
            --secondary-color: #7e103dff;
            --text-color: #1f2937;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
            --sidebar-bg: linear-gradient(to bottom, #1e40af, #1e3a8a);
            --hover-effect: translateX(-5px);
        }

        [data-theme="dark"] {
            --primary-color: #fa60edff;
            --secondary-color: #8d0865ff;
            --text-color: #e5e7eb;
            --bg-color: #111827;
            --card-bg: #1f2937;
            --sidebar-bg: linear-gradient(to bottom, #111827, #0f172a);
            --hover-effect: translateX(-5px);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar-item {
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            transform: var(--hover-effect);
            background-color: var(--primary-color) !important;
            color: white !important;
        }

        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: var(--card-bg);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .notification-dot {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            background-color: #ef4444;
            border-radius: 50%;
        }

        .theme-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .theme-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #1e40af;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                bottom: 0;
                width: 80%;
                z-index: 50;
                transition: left 0.3s ease;
            }

            .sidebar.open {
                left: 0;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            .overlay.open {
                display: block;
            }

            .menu-btn {
                display: block;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                margin-top: 1rem;
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (min-width: 769px) {
            .menu-btn {
                display: none;
            }

            .overlay {
                display: none !important;
            }
        }
    </style>
</head>
<body class="flex h-screen text-gray-800">
    <!-- زر القائمة للشاشات الصغيرة -->
    <button class="menu-btn fixed top-4 left-4 z-50 p-2 bg-blue-600 text-white rounded-md md:hidden">
        <span class="material-icons">menu</span>
    </button>

    <!-- Overlay للشاشات الصغيرة -->
    <div class="overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar w-64 bg-gradient-to-b from-blue-800 to-blue-900 shadow-xl flex flex-col">
        <div class="p-6 text-2xl font-bold border-b border-blue-700 text-white flex items-center justify-between">
            <div class="flex items-center">
                <span class="material-icons mr-2">dashboard</span>
                لوحة تحكم المدير
            </div>
            <button class="close-btn text-white md:hidden">
                <span class="material-icons">close</span>
            </button>
        </div>
        <nav class="flex-1 p-4 mt-4">
            <a href="/employee-portal/public/manager/dashboard" class="flex items-center sidebar-item p-3 mb-3 rounded-lg text-white hover:bg-blue-700 hover:shadow-md">
                <span class="material-icons mr-3">home</span> 
                <span>الرئيسية</span>
            </a>
            
            <a href="/employee-portal/public/manager/profile" class="flex items-center sidebar-item p-3 mb-3 rounded-lg text-white hover:bg-blue-700 hover:shadow-md">
                <span class="material-icons mr-3">person</span> 
                <span>الملف الشخصي</span>
            </a>
            
            <a href="/employee-portal/public/manager/employees" class="flex items-center sidebar-item p-3 mb-3 rounded-lg text-white hover:bg-blue-700 hover:shadow-md">
                <span class="material-icons mr-3">people</span> 
                <span>الموظفين</span>
                <?php if (($employeeStats['total'] ?? 0) > 0): ?>
                    <span class="mr-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                        <?= $employeeStats['total'] ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="/employee-portal/public/manager/leaves" class="flex items-center sidebar-item p-3 mb-3 rounded-lg text-white hover:bg-blue-700 hover:shadow-md">
                <span class="material-icons mr-3">event</span> 
                <span>الإجازات</span>
                <?php 
                $pendingLeaves = array_filter($leaveStats['byStatus'] ?? [], function($status) {
                    return $status['status'] === 'pending';
                });
                $pendingCount = !empty($pendingLeaves) ? current($pendingLeaves)['count'] : 0;
                ?>
                <?php if ($pendingCount > 0): ?>
                    <span class="mr-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                        <?= $pendingCount ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="/employee-portal/public/manager/salaries" class="flex items-center sidebar-item p-3 mb-3 rounded-lg text-white hover:bg-blue-700 hover:shadow-md">
                <span class="material-icons mr-3">paid</span> 
                <span>الرواتب</span>
                <?php 
                $pendingSalaries = array_filter($salaryStats['byStatus'] ?? [], function($status) {
                    return $status['status'] === 'pending';
                });
                $pendingSalaryCount = !empty($pendingSalaries) ? current($pendingSalaries)['count'] : 0;
                ?>
                <?php if ($pendingSalaryCount > 0): ?>
                    <span class="mr-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                        <?= $pendingSalaryCount ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="/employee-portal/public/manager/tasks" class="flex items-center sidebar-item p-3 mb-3 rounded-lg text-white hover:bg-blue-700 hover:shadow-md">
                <span class="material-icons mr-3">task</span> 
                <span>المهام</span>
                <?php if (($taskStats['pending'] ?? 0) > 0): ?>
                    <span class="mr-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                        <?= $taskStats['pending'] ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="/employee-portal/public/manager/evaluations" class="flex items-center sidebar-item p-3 mb-3 rounded-lg text-white hover:bg-blue-700 hover:shadow-md">
                <span class="material-icons mr-3">star</span> 
                <span>التقييمات</span>
            </a>
            
            <a href="/employee-portal/public/manager/leave-request" class="flex items-center sidebar-item p-3 mb-3 rounded-lg text-white hover:bg-blue-700 hover:shadow-md">
                <span class="material-icons mr-3">event_available</span> 
                <span>طلب إجازة</span>
            </a>
        </nav>
        <div class="p-4 border-t border-blue-700">
            <div class="flex items-center justify-between mb-4">
                <span class="text-white">الوضع الليلي</span>
                <label class="theme-switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
            </div>
            <a href="/employee-portal/public/logout" class="flex items-center p-3 text-white rounded-lg hover:bg-red-600 transition-colors">
                <span class="material-icons mr-3">logout</span> 
                <span>تسجيل الخروج</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center">
            <div class="header-content flex flex-col md:flex-row md:items-center">
                <h1 class="text-2xl font-bold text-gray-700">
                    مرحبًا <?= htmlspecialchars($userName) ?> 
                </h1>
                <p class="text-blue-600 md:mr-4"><?= htmlspecialchars($userRole) ?></p>
            </div>
            <div class="header-actions flex items-center">
                <div class="relative mr-4">
                    <span class="material-icons text-gray-600">notifications</span>
                    <?php 
                    $totalNotifications = ($pendingCount + $pendingSalaryCount + ($taskStats['pending'] ?? 0));
                    if ($totalNotifications > 0): ?>
                        <span class="notification-dot"></span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-bold">
                        <?= substr($userName, 0, 1) ?>
                    </div>
                    <div class="mr-3">
                        <p class="text-sm font-medium"><?= htmlspecialchars($userName) ?></p>
                        <p class="text-xs text-gray-500">مدير</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="p-6">
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
            <!-- Content Section -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <?= $content ?? '
                <div class="text-center py-12">
                    <span class="material-icons text-gray-400 text-5xl mb-3">dashboard</span>
                    <h2 class="text-2xl font-bold text-gray-700 mb-2">مرحبًا في لوحة تحكم المدير</h2>
                    <p class="text-gray-500">اختر قسمًا من القائمة الجانبية لبدء إدارة فريقك</p>
                </div>
                ' ?>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8 mt-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">إجراءات سريعة</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="/employee-portal/public/manager/employees" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <span class="material-icons text-blue-600 text-3xl mb-2">groups</span>
                        <span class="text-blue-800 font-medium">إدارة الموظفين</span>
                    </a>
                    <a href="/employee-portal/public/manager/leaves" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <span class="material-icons text-green-600 text-3xl mb-2">event</span>
                        <span class="text-green-800 font-medium">مراجعة الإجازات</span>
                    </a>
                    <a href="/employee-portal/public/manager/tasks" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <span class="material-icons text-purple-600 text-3xl mb-2">assignment</span>
                        <span class="text-purple-800 font-medium">توزيع المهام</span>
                    </a>
                    <a href="/employee-portal/public/manager/leave-request" class="flex flex-col items-center justify-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                        <span class="material-icons text-orange-600 text-3xl mb-2">beach_access</span>
                        <span class="text-orange-800 font-medium">طلب إجازة</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        // إعداد نظام الوضع الليلي والنهاري
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const body = document.body;
            
            // التحقق من التفضيل المحفوظ
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                body.setAttribute('data-theme', 'dark');
                themeToggle.checked = true;
            } else {
                body.removeAttribute('data-theme');
                themeToggle.checked = false;
            }
            
            // تغيير الوضع عند النقر على المفتاح
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    body.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    body.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                }
            });
            
            // إدارة القائمة الجانبية للشاشات الصغيرة
            const menuBtn = document.querySelector('.menu-btn');
            const closeBtn = document.querySelector('.close-btn');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');
            
            menuBtn.addEventListener('click', function() {
                sidebar.classList.add('open');
                overlay.classList.add('open');
            });
            
            closeBtn.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            });
            
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            });
            
            // إغلاق القائمة عند النقر على رابط
            const sidebarLinks = document.querySelectorAll('.sidebar-item');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                });
            });
        });
    </script>
</body>
</html>

 