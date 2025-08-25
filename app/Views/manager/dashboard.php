<div class="container mx-auto">

    <!-- الإشعارات -->
    <?php if ($stats['pending_leaves'] > 0 || $stats['pending_salaries'] > 0): ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-yellow-400"></i>
            </div>
            <div class="mr-3">
                <p class="text-sm text-yellow-700">
                    لديك 
                    <?php if ($stats['pending_leaves'] > 0): ?>
                        <strong><?= $stats['pending_leaves'] ?> طلب إجازة</strong>
                    <?php endif; ?>
                    <?php if ($stats['pending_leaves'] > 0 && $stats['pending_salaries'] > 0): ?>
                        و
                    <?php endif; ?>
                    <?php if ($stats['pending_salaries'] > 0): ?>
                        <strong><?= $stats['pending_salaries'] ?> طلب راتب</strong>
                    <?php endif; ?>
                    بانتظار المراجعة
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- إحصائيات حالية... -->
    </div>
     
    <!-- المحتوى الرئيسي -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- طلبات الإجازة الأخيرة -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">أحدث طلبات الإجازة</h2>
                <a href="/employee-portal/public/manager/leaves" class="text-blue-600 text-sm">عرض الكل</a>
            </div>
            <div class="space-y-3">
                <?php foreach (array_slice($leaves, 0, 5) as $leave): ?>
                <div class="border-b pb-3">
                    <div class="flex justify-between items-center">
                        <span class="font-medium"><?= htmlspecialchars($leave['employee_name']) ?></span>
                        <span class="px-2 py-1 rounded text-xs 
                            <?= $leave['status'] === 'approved' ? 'bg-green-100 text-green-800' : '' ?>
                            <?= $leave['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                            <?= $leave['status'] === 'rejected' ? 'bg-red-100 text-red-800' : '' ?>">
                            <?= $leave['status'] ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-600"><?= $leave['days_requested'] ?> يوم (<?= $leave['start_date'] ?> إلى <?= $leave['end_date'] ?>)</p>
                    <?php if ($leave['status'] === 'pending'): ?>
                    <div class="mt-2">
                        <a href="/employee-portal/public/manager/leaves" class="text-blue-600 text-sm">مراجعة الطلب</a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
         
        <!-- المهام الأخيرة -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">أحدث المهام</h2>
                <a href="/employee-portal/public/manager/tasks" class="text-blue-600 text-sm">عرض الكل</a>
            </div>
            <div class="space-y-3">
                <?php foreach (array_slice($tasks, 0, 5) as $task): ?>
                <div class="border-b pb-3">
                    <div class="flex justify-between items-center">
                        <span class="font-medium"><?= htmlspecialchars($task['title']) ?></span>
                        <span class="px-2 py-1 rounded text-xs 
                            <?= $task['status'] === 'done' ? 'bg-green-100 text-green-800' : '' ?>
                            <?= $task['status'] === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' ?>
                            <?= $task['status'] === 'todo' ? 'bg-gray-100 text-gray-800' : '' ?>">
                            <?= $task['status'] ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">لـ <?= htmlspecialchars($task['employee_name']) ?></p>
                    <?php if ($task['due_date'] && strtotime($task['due_date']) < strtotime('+3 days')): ?>
                    <p class="text-sm text-red-600">موعد التسليم: <?= $task['due_date'] ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- التقييمات الأخيرة -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">أحدث التقييمات</h2>
                <a href="/employee-portal/public/manager/evaluations" class="text-blue-600 text-sm">عرض الكل</a>
            </div>
            <div class="space-y-3">
                <?php foreach (array_slice($evaluations, 0, 5) as $eval): ?>
                <?php
                $averageScore = ($eval['performance_score'] + $eval['quality_score'] + 
                               $eval['punctuality_score'] + $eval['teamwork_score']) / 4;
                $ratingColor = $averageScore >= 4 ? 'text-green-600' : 
                              ($averageScore >= 3 ? 'text-yellow-600' : 'text-red-600');
                ?>
                <div class="border-b pb-3">
                    <div class="flex justify-between items-center">
                        <span class="font-medium"><?= htmlspecialchars($eval['employee_name']) ?></span>
                        <span class="text-lg font-bold <?= $ratingColor ?>">
                            <?= number_format($averageScore, 1) ?>/5
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">بواسطة <?= htmlspecialchars($eval['evaluator_name']) ?></p>
                    <p class="text-sm text-gray-500"><?= $eval['evaluation_date'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- طلبات الرواتب الأخيرة -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">أحدث طلبات الرواتب</h2>
                <a href="/employee-portal/public/manager/salaries" class="text-blue-600 text-sm">عرض الكل</a>
            </div>
            <div class="space-y-3">
                <?php foreach (array_slice($salaries, 0, 5) as $salary): ?>
                <div class="border-b pb-3">
                    <div class="flex justify-between items-center">
                        <span class="font-medium"><?= htmlspecialchars($salary['employee_name']) ?></span>
                        <span class="px-2 py-1 rounded text-xs 
                            <?= $salary['status'] === 'approved' ? 'bg-green-100 text-green-800' : '' ?>
                            <?= $salary['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                            <?= $salary['status'] === 'rejected' ? 'bg-red-100 text-red-800' : '' ?>">
                            <?= $salary['status'] ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-600"><?= number_format($salary['amount'], 2) ?> ريال</p>
                    <?php if ($salary['status'] === 'pending'): ?>
                    <div class="mt-2">
                        <a href="/employee-portal/public/manager/salaries" class="text-blue-600 text-sm">مراجعة الطلب</a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- إجراءات سريعة -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="/employee-portal/public/manager/leaves" class="bg-white p-4 rounded-lg shadow text-center hover:bg-gray-50">
            <i class="fas fa-calendar-alt text-blue-600 text-2xl mb-2"></i>
            <p>إدارة الإجازات</p>
        </a>
        <a href="/employee-portal/public/manager/salaries" class="bg-white p-4 rounded-lg shadow text-center hover:bg-gray-50">
            <i class="fas fa-money-bill-wave text-green-600 text-2xl mb-2"></i>
            <p>إدارة الرواتب</p>
        </a>
        <a href="/employee-portal/public/manager/tasks" class="bg-white p-4 rounded-lg shadow text-center hover:bg-gray-50">
            <i class="fas fa-tasks text-purple-600 text-2xl mb-2"></i>
            <p>إدارة المهام</p>
        </a>
        <a href="/employee-portal/public/manager/evaluations" class="bg-white p-4 rounded-lg shadow text-center hover:bg-gray-50">
            <i class="fas fa-chart-line text-yellow-600 text-2xl mb-2"></i>
            <p>التقييمات</p>
        </a>
    </div>
</div>