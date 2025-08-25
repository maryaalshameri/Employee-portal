<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل الرواتب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
      <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #059669;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .table-row {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .table-row:hover {
            background-color: #f8fafc;
            transform: translateX(-5px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(52, 152, 219, 0.4);
        }

        .empty-state {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #10b981;
            border-radius: 12px;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #ef4444;
            border-radius: 12px;
        }

        .amount-cell {
            font-feature-settings: 'tnum';
            font-variant-numeric: tabular-nums;
        }

        .net-salary {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-radius: 8px;
            padding: 0.5rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }
            
            .responsive-table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body class="py-8 px-4">
    <div class="container mx-auto max-w-6xl">
        <!-- رسائل التنبيه -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-success px-4 py-3 mb-6 flex items-center">
                <i class="fas fa-check-circle ml-2 text-green-600"></i>
                <span class="text-green-800"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-error px-4 py-3 mb-6 flex items-center">
                <i class="fas fa-exclamation-circle ml-2 text-red-600"></i>
                <span class="text-red-800"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <!-- العنوان الرئيسي -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-file-invoice-dollar ml-2 text-green-500"></i>
                    سجل الرواتب
                </h1>
                <p class="text-gray-600 mt-2">عرض جميع طلبات الرواتب السابقة</p>
            </div>
            <a href="/employee-portal/public/employee" class="btn-primary text-white px-6 py-2 rounded-lg mt-4 md:mt-0 flex items-center">
                <i class="fas fa-home ml-2"></i>
                رجوع إلى الرئيسية
            </a>
        </div>

        <!-- بطاقة سجل الرواتب -->
        <div class="main-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-money-bill-wave ml-2 text-green-500"></i>
                    جميع طلبات الرواتب
                </h2>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                    <?= count($salaries) ?> طلب
                </span>
            </div>

            <?php if (!empty($salaries)): ?>
            <div class="table-container">
                <table class="w-full responsive-table">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">المبلغ الأساسي</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">المكافأة</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الخصم</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الصافي</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">تاريخ الدفع</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">الحالة</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 uppercase">تاريخ الطلب</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($salaries as $salary): ?>
                        <?php
                        $netSalary = $salary['amount'] + 
                                   ($salary['amount'] * $salary['bonusPercentage'] / 100) - 
                                   ($salary['amount'] * $salary['deductionPercentage'] / 100);
                        ?>
                        <tr class="table-row">
                            <td class="px-6 py-4 amount-cell">
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill text-blue-500 ml-2"></i>
                                    <span class="text-blue-800 font-medium"><?= number_format($salary['amount'], 2) ?> ر.ي</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    +<?= $salary['bonusPercentage'] ?>%
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                    -<?= $salary['deductionPercentage'] ?>%
                                </span>
                            </td>
                            <td class="px-6 py-4 amount-cell">
                                <div class="net-salary text-center">
                                    <span class="text-green-800"><?= number_format($netSalary, 2) ?> ر.ي</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($salary['payment_date']): ?>
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                        <?= $salary['payment_date'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm">لم يتم الدفع بعد</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $statusClass = [
                                    'approved' => 'status-approved',
                                    'pending' => 'status-pending',
                                    'rejected' => 'status-rejected'
                                ];
                                $statusIcon = [
                                    'approved' => 'fas fa-check-circle',
                                    'pending' => 'fas fa-clock',
                                    'rejected' => 'fas fa-times-circle'
                                ];
                                ?>
                                <span class="status-badge <?= $statusClass[$salary['status']] ?>">
                                    <i class="<?= $statusIcon[$salary['status']] ?> ml-1"></i>
                                    <?= $salary['status'] === 'approved' ? 'مقبول' : 
                                       ($salary['status'] === 'pending' ? 'قيد المراجعة' : 'مرفوض') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500">
                                    <i class="far fa-calendar-alt ml-1 text-gray-400"></i>
                                    <?= date('Y-m-d', strtotime($salary['created_at'])) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- إحصائيات الرواتب -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <?php
                    $totalApproved = array_filter($salaries, fn($s) => $s['status'] === 'approved');
                    $totalPending = array_filter($salaries, fn($s) => $s['status'] === 'pending');
                    $totalRejected = array_filter($salaries, fn($s) => $s['status'] === 'rejected');
                    
                    $totalNetSalary = array_reduce($totalApproved, function($sum, $salary) {
                        $net = $salary['amount'] + 
                              ($salary['amount'] * $salary['bonusPercentage'] / 100) - 
                              ($salary['amount'] * $salary['deductionPercentage'] / 100);
                        return $sum + $net;
                    }, 0);
                    ?>
                    
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">
                            <?= count($totalApproved) ?>
                        </div>
                        <p class="text-sm text-green-800">طلبات مقبولة</p>
                    </div>
                    
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">
                            <?= count($totalPending) ?>
                        </div>
                        <p class="text-sm text-yellow-800">طلبات قيد المراجعة</p>
                    </div>
                    
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">
                            <?= count($totalRejected) ?>
                        </div>
                        <p class="text-sm text-red-800">طلبات مرفوضة</p>
                    </div>
                    
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">
                            <?= number_format($totalNetSalary, 2) ?> ر.ي
                        </div>
                        <p class="text-sm text-blue-800">إجمالي الصافي</p>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="empty-state text-center py-12">
                <i class="fas fa-file-invoice-dollar text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">لا توجد طلبات رواتب سابقة</h3>
                <p class="text-gray-400 mb-4">لم تقم بتقديم أي طلبات رواتب حتى الآن</p>
            </div>
            <?php endif; ?>
        </div>
    </div>


    <script>
        // إدارة القائمة الجانبية للشاشات الصغيرة
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.querySelector('.mobile-menu-btn');
            const closeBtn = document.querySelector('.close-btn');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');
            
            if (menuBtn) {
                menuBtn.addEventListener('click', function() {
                    sidebar.classList.add('open');
                    overlay.classList.add('open');
                });
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                });
            }
            
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                });
            }
            
            // تأثيرات عند التحميل
            const tableRows = document.querySelectorAll('.table-row');
            
            tableRows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    row.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        row.style.opacity = '1';
                        row.style.transform = 'translateX(0)';
                    }, 50);
                }, index * 100);
            });
        });
    </script>
</body>
</html>