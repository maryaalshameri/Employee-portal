<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الموظفين - لوحة تحكم الموظف</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #2c3e50;
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-left: 4px solid transparent;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
            border-left: 4px solid #3498db;
        }
        .sidebar .nav-link.active {
            background-color: #34495e;
            border-left: 4px solid #3498db;
        }
        .navbar-custom {
            background-color: #3498db;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- الشريط الجانبي -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="text-center p-4">
                    <h5>نظام إدارة الموظفين</h5>
                    <p class="text-muted">لوحة تحكم الموظف</p>
                </div>
                
        <nav class="nav flex-column">
            <a class="nav-link" href="/employee-portal/public/employee">
                <i class="fas fa-tachometer-alt me-2"></i> الرئيسية
            </a>
            <a class="nav-link" href="/employee-portal/public/employee/profile">
                <i class="fas fa-user me-2"></i> الملف الشخصي
            </a>
            <a class="nav-link" href="/employee-portal/public/employee/tasks">
                <i class="fas fa-tasks me-2"></i> المهام
            </a>
            <a class="nav-link" href="/employee-portal/public/employee/leave-request">
                <i class="fas fa-calendar-plus me-2"></i> طلب إجازة
            </a>
            <a class="nav-link" href="/employee-portal/public/employee/leaves">
                <i class="fas fa-history me-2"></i> سجل الإجازات
            </a>
            <a class="nav-link" href="/employee-portal/public/employee/salaries">
                <i class="fas fa-file-invoice-dollar me-2"></i> سجل الرواتب
            </a>
            <a class="nav-link" href="/employee-portal/public/logout">
                <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
            </a>
        </nav>
            </div>

            <!-- المحتوى الرئيسي -->
            <div class="col-md-9 col-lg-10 main-content p-0">
                <!-- شريط التنقل العلوي -->
                <nav class="navbar navbar-expand-lg navbar-custom">
                    <div class="container-fluid">
                        <span class="navbar-brand text-white">
                            <i class="fas fa-user me-2"></i>مرحباً، <?= $userName ?>
                        </span>
                        
                        <div class="d-flex">
                            <span class="badge bg-light text-dark me-3">
                                <i class="fas fa-user-tag me-1"></i> <?= $userRole ?>
                            </span>
                            <a href="/employee-portal/public/logout" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-sign-out-alt me-1"></i> خروج
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- محتوى الصفحة -->
                <div class="container-fluid p-4">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تفعيل العنصر النشط في القائمة
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.href.includes(currentPath)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>