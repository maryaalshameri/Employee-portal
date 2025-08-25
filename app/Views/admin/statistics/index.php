<?php
// متغيرات افتراضية لتجنب الأخطاء
$employeeStats = $employeeStats ?? [];
$leaveStats = $leaveStats ?? [];
$salaryStats = $salaryStats ?? [];
$userStats = $userStats ?? [];
$generalStats = $generalStats ?? [];
?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-3xl font-bold mb-8 text-gray-800 border-b-2 border-blue-200 pb-4">
        <span class="material-icons text-blue-500 align-middle mr-2">analytics</span>
        لوحة الإحصائيات الشاملة
    </h2>
    
    
    <!-- الإحصائيات العامة -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-blue-800 font-semibold text-sm">إجمالي الموظفين</h3>
                    <p class="text-3xl text-blue-600 font-bold"><?= number_format($generalStats['systemStats']['total_employees'] ?? 0) ?></p>
                </div>
                <span class="material-icons text-blue-500 text-4xl">people</span>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg border border-green-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-green-800 font-semibold text-sm">إجمالي الإجازات</h3>
                    <p class="text-3xl text-green-600 font-bold"><?= number_format($generalStats['systemStats']['total_leaves'] ?? 0) ?></p>
                </div>
                <span class="material-icons text-green-500 text-4xl">event</span>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-purple-800 font-semibold text-sm">إجمالي الرواتب</h3>
                    <p class="text-3xl text-purple-600 font-bold"><?= number_format($generalStats['systemStats']['total_salaries'] ?? 0) ?></p>
                </div>
                <span class="material-icons text-purple-500 text-4xl">payments</span>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-lg border border-orange-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-orange-800 font-semibold text-sm">إجمالي المستخدمين</h3>
                    <p class="text-3xl text-orange-600 font-bold"><?= number_format($generalStats['systemStats']['total_users'] ?? 0) ?></p>
                </div>
                <span class="material-icons text-orange-500 text-4xl">group</span>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- إحصائيات الموظفين -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <span class="material-icons text-blue-500 mr-2">people</span>
                إحصائيات الموظفين
            </h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600">العدد الإجمالي</h4>
                    <p class="text-2xl font-bold text-blue-600"><?= number_format($employeeStats['total'] ?? 0) ?></p>
                </div>
                
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600">حسب الأقسام</h4>
                    <div class="mt-2 space-y-1">
                        <?php foreach ($employeeStats['byDepartment'] ?? [] as $dept): ?>
                            <div class="flex justify-between text-sm">
                                <span><?= $dept['department'] ?></span>
                                <span class="font-medium"><?= $dept['count'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded shadow-sm">
                <h4 class="text-sm font-medium text-gray-600 mb-2">أحدث الموظفين</h4>
                <div class="space-y-2">
                    <?php foreach ($employeeStats['recentEmployees'] ?? [] as $employee): ?>
                        <div class="flex justify-between items-center text-sm p-2 bg-gray-50 rounded">
                            <span><?= htmlspecialchars($employee['name']) ?></span>
                            <span class="text-xs text-gray-500"><?= $employee['department'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- إحصائيات الإجازات -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <span class="material-icons text-green-500 mr-2">event</span>
                إحصائيات الإجازات
            </h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600">العدد الإجمالي</h4>
                    <p class="text-2xl font-bold text-green-600"><?= number_format($leaveStats['total'] ?? 0) ?></p>
                </div>
                
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600">إجمالي الأيام</h4>
                    <p class="text-2xl font-bold text-green-600"><?= number_format($leaveStats['totalDays'] ?? 0) ?></p>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded shadow-sm mb-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">حسب الحالة</h4>
                <div class="space-y-2">
                    <?php foreach ($leaveStats['byStatus'] ?? [] as $status): ?>
                        <div class="flex justify-between items-center text-sm">
                            <span class="<?= $status['status'] === 'approved' ? 'text-green-600' : 
                                        ($status['status'] === 'rejected' ? 'text-red-600' : 'text-yellow-600') ?>">
                                <?= $status['status'] === 'approved' ? 'موافق' : 
                                    ($status['status'] === 'rejected' ? 'مرفوض' : 'قيد الانتظار') ?>
                            </span>
                            <span class="font-medium"><?= $status['count'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- إحصائيات الرواتب -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <span class="material-icons text-purple-500 mr-2">payments</span>
                إحصائيات الرواتب
            </h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600">العدد الإجمالي</h4>
                    <p class="text-2xl font-bold text-purple-600"><?= number_format($salaryStats['total'] ?? 0) ?></p>
                </div>
                
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600">متوسط الراتب</h4>
                    <p class="text-2xl font-bold text-purple-600"><?= number_format($salaryStats['avgSalary'] ?? 0, 2) ?> ر.س</p>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded shadow-sm">
                <h4 class="text-sm font-medium text-gray-600 mb-2">المبالغ الإجمالية</h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>إجمالي الرواتب:</span>
                        <span class="font-bold"><?= number_format($salaryStats['amounts']['total_amount'] ?? 0, 2) ?> ر.س</span>
                    </div>
                    <div class="flex justify-between text-sm text-green-600">
                        <span>إجمالي المكافآت:</span>
                        <span class="font-bold">+ <?= number_format($salaryStats['amounts']['total_bonus'] ?? 0, 2) ?> ر.س</span>
                    </div>
                    <div class="flex justify-between text-sm text-red-600">
                        <span>إجمالي الخصومات:</span>
                        <span class="font-bold">- <?= number_format($salaryStats['amounts']['total_deductions'] ?? 0, 2) ?> ر.س</span>
                    </div>
                    <div class="flex justify-between text-sm border-t pt-2">
                        <span class="font-bold">الصافي:</span>
                        <span class="font-bold text-lg"><?= number_format($salaryStats['amounts']['net_total'] ?? 0, 2) ?> ر.س</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- إحصائيات المستخدمين والمحذوفات -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <span class="material-icons text-orange-500 mr-2">group</span>
                إحصائيات المستخدمين
            </h3>
            
            <div class="bg-white p-4 rounded shadow-sm mb-4">
                <h4 class="text-sm font-medium text-gray-600">العدد الإجمالي</h4>
                <p class="text-2xl font-bold text-orange-600"><?= number_format($userStats['total'] ?? 0) ?></p>
            </div>
            
            <div class="bg-white p-4 rounded shadow-sm mb-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">حسب الدور</h4>
                <div class="space-y-2">
                    <?php foreach ($userStats['byRole'] ?? [] as $role): ?>
                        <div class="flex justify-between text-sm">
                            <span class="<?= $role['role'] === 'admin' ? 'text-red-600' : 
                                        ($role['role'] === 'manager' ? 'text-blue-600' : 'text-green-600') ?>">
                                <?= $role['role'] === 'admin' ? 'مدير' : 
                                    ($role['role'] === 'manager' ? 'مشرف' : 'موظف') ?>
                            </span>
                            <span class="font-medium"><?= $role['count'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- سلة المحذوفات -->
            <div class="bg-red-50 p-4 rounded border border-red-200">
                <h4 class="text-sm font-medium text-red-800 mb-2 flex items-center">
                    <span class="material-icons text-red-600 mr-2">delete</span>
                    سلة المحذوفات
                </h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>موظفين محذوفين:</span>
                        <span class="font-medium text-red-600"><?= $generalStats['trashCounts']['employees_trash'] ?? 0 ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>إجازات محذوفة:</span>
                        <span class="font-medium text-red-600"><?= $generalStats['trashCounts']['leaves_trash'] ?? 0 ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>رواتب محذوفة:</span>
                        <span class="font-medium text-red-600"><?= $generalStats['trashCounts']['salaries_trash'] ?? 0 ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- أزرار التنقل -->
    <div class="mt-8 flex flex-wrap gap-4 justify-center">
        <a href="/employee-portal/public/admin/employees" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center transition-colors">
            <span class="material-icons mr-2">people</span>
            إدارة الموظفين
        </a>
        <a href="/employee-portal/public/admin/leaves" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg flex items-center transition-colors">
            <span class="material-icons mr-2">event</span>
            إدارة الإجازات
        </a>
        <a href="/employee-portal/public/admin/salaries" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg flex items-center transition-colors">
            <span class="material-icons mr-2">payments</span>
            إدارة الرواتب
        </a>
        <a href="/employee-portal/public/admin" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition-colors">
            <span class="material-icons mr-2">dashboard</span>
            لوحة التحكم
        </a>
    </div>
</div>

<style>
.material-icons {
    font-size: 20px;
    vertical-align: middle;
}
</style>