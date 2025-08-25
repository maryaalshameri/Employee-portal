
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-800">إدارة الموظفين</h1>
            <div class="flex flex-col items-center gap-3 space-x-4 space-x-reverse md:flex-row">
                <a href="/employee-portal/public/" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg flex items-center">
                    <span class="material-icons mr-2">home</span>
                    الرئيسية
                </a>
                <a href="/employee-portal/public/logout" class="bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 rounded-lg flex items-center">
                    <span class="material-icons mr-2">logout</span>
                    تسجيل الخروج
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 mb-6">
            <a href="/employee-portal/public/admin/employees/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center hover-effect">
                <span class="material-icons mr-2">person_add</span>
                إضافة موظف جديد
            </a>
            <a href="/employee-portal/public/admin/employees/trash" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center hover-effect">
                <span class="material-icons mr-2">delete</span>
                سلة المهملات
                <?php if (($trashCount ?? 0) > 0): ?>
                    <span class="mr-2 bg-red-800 text-white text-xs px-2 py-1 rounded-full"><?= $trashCount ?></span>
                <?php endif; ?>
            </a>
            <a href="/employee-portal/public/admin" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center hover-effect">
                <span class="material-icons mr-2">dashboard</span>
                لوحة التحكم
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-4 border-r-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-600">إجمالي الموظفين</h3>
                    <span class="material-icons text-blue-500">people</span>
                </div>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?= count($employees) ?></p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4 border-r-4 border-green-500">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-600">المدراء</h3>
                    <span class="material-icons text-green-500">supervisor_account</span>
                </div>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    <?= count(array_filter($employees, function($emp) { return $emp['role'] === 'manager'; })) ?>
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4 border-r-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-600">الموظفون</h3>
                    <span class="material-icons text-purple-500">badge</span>
                </div>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    <?= count(array_filter($employees, function($emp) { return $emp['role'] === 'employee'; })) ?>
                </p>
            </div>
        </div>

        <!-- Employees Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوظيفة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الراتب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($employees)): ?>
                            <?php foreach ($employees as $emp): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-800 font-bold">
                                                <?= substr($emp['name'], 0, 1) ?>
                                            </div>
                                            <div class="mr-4">
                                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($emp['name']) ?></div>
                                                <div class="text-sm text-gray-500"><?= htmlspecialchars($emp['work_type']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($emp['email']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $emp['role'] === 'manager' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' ?>">
                                            <?= $emp['role'] === 'manager' ? 'مدير' : 'موظف' ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($emp['department']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($emp['position']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="font-medium"><?= number_format($emp['salary'], 2) ?> ر.س</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2 space-x-reverse">
                                            <a href="/employee-portal/public/admin/employees/edit/<?= $emp['id'] ?>" 
                                               class="text-blue-600 hover:text-blue-900 flex items-center">
                                                <span class="material-icons text-base mr-1">edit</span>
                                                
                                            </a>
                                            <a href="/employee-portal/public/admin/employees/delete/<?= $emp['id'] ?>" 
                                               class="text-red-600 hover:text-red-900 flex items-center"
                                               onclick="return confirm('هل تريد حذف هذا الموظف؟')">
                                                <span class="material-icons text-base mr-1">delete</span>
                                                
                                            </a>
                                            <a href="/employee-portal/public/admin/employees/record/<?= $emp['id'] ?>" 
                                               class="text-green-600 hover:text-green-900 flex items-center">
                                                <span class="material-icons text-base mr-1">visibility</span>
                                                
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <span class="material-icons text-gray-400 text-4xl mb-3">people_outline</span>
                                        <p>لا يوجد موظفين مسجلين في النظام</p>
                                        <a href="/employee-portal/public/admin/employees/create" class="text-blue-600 hover:text-blue-800 mt-2">
                                            إضافة موظف جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional Info (for larger screens) -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">التوزيع حسب الأقسام</h3>
                <div class="space-y-3">
                    <?php
                    $departments = [];
                    foreach ($employees as $emp) {
                        $dept = $emp['department'];
                        if (!isset($departments[$dept])) {
                            $departments[$dept] = 0;
                        }
                        $departments[$dept]++;
                    }
                    ?>
                    <?php if (!empty($departments)): ?>
                        <?php foreach ($departments as $dept => $count): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600"><?= htmlspecialchars($dept) ?></span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full"><?= $count ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-center">لا توجد أقسام</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">التوزيع حسب نوع الدوام</h3>
                <div class="space-y-3">
                    <?php
                    $workTypes = [];
                    foreach ($employees as $emp) {
                        $type = $emp['work_type'];
                        if (!isset($workTypes[$type])) {
                            $workTypes[$type] = 0;
                        }
                        $workTypes[$type]++;
                    }
                    ?>
                    <?php if (!empty($workTypes)): ?>
                        <?php foreach ($workTypes as $type => $count): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">
                                    <?= $type === 'full-time' ? 'دوام كامل' : 
                                         ($type === 'part-time' ? 'دوام جزئي' : 
                                         ($type === 'freelance' ? 'عمل حر' : $type)) ?>
                                </span>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full"><?= $count ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-center">لا توجد بيانات</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // تأكيد الحذف
        // function confirmDelete(event) {
        //     if (!confirm('هل أنت متأكد من أنك تريد حذف هذا الموظف؟')) {
        //         event.preventDefault();
        //     }
        // }
        
        // إضافة مستمعين الأحداث للروابط
        // document.querySelectorAll('a[onclick*="confirm"]').forEach(link => {
        //     link.addEventListener('click', function(e) {
        //         if (!confirm('هل أنت متأكد من أنك تريد حذف هذا الموظف؟')) {
        //             e.preventDefault();
        //         }
        //     });
        // });
    </script>
</body>
