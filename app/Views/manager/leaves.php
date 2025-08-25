<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">إجازات قسم <?= htmlspecialchars($manager['department']) ?></h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموظف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع الإجازة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">من</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إلى</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الأيام</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السبب</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الملاحظات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الطلب</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($leaves as $leave): ?>
                <tr>
                    <td class="px-6 py-4"><?= htmlspecialchars($leave['employee_name']) ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs 
                            <?= $leave['type'] === 'annual' ? 'bg-blue-100 text-blue-800' : '' ?>
                            <?= $leave['type'] === 'sick' ? 'bg-red-100 text-red-800' : '' ?>
                            <?= $leave['type'] === 'emergency' ? 'bg-orange-100 text-orange-800' : '' ?>
                            <?= $leave['type'] === 'other' ? 'bg-gray-100 text-gray-800' : '' ?>">
                            <?= $leave['type'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4"><?= $leave['start_date'] ?></td>
                    <td class="px-6 py-4"><?= $leave['end_date'] ?></td>
                    <td class="px-6 py-4"><?= $leave['days_requested'] ?> يوم</td>
                    <td class="px-6 py-4"><?= htmlspecialchars($leave['reason']) ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs 
                            <?= $leave['status'] === 'approved' ? 'bg-green-100 text-green-800' : '' ?>
                            <?= $leave['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                            <?= $leave['status'] === 'rejected' ? 'bg-red-100 text-red-800' : '' ?>">
                            <?= $leave['status'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4"><?= $leave['comments'] ?></td>

                    <td class="px-6 py-4"><?= date('Y-m-d', strtotime($leave['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- إحصائيات الإجازات -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-2">إجمالي طلبات الإجازة</h3>
            <p class="text-2xl text-blue-600 font-bold"><?= count($leaves) ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-2">المعتمدة</h3>
            <p class="text-2xl text-green-600 font-bold">
                <?= count(array_filter($leaves, function($l) { return $l['status'] === 'approved'; })) ?>
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-2">قيد المراجعة</h3>
            <p class="text-2xl text-yellow-600 font-bold">
                <?= count(array_filter($leaves, function($l) { return $l['status'] === 'pending'; })) ?>
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-2">إجمالي الأيام</h3>
            <p class="text-2xl text-purple-600 font-bold">
                <?= array_sum(array_column($leaves, 'days_requested')) ?> يوم
            </p>
        </div>
    </div>

    <!-- تحليل أنواع الإجازات -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-bold mb-4">توزيع أنواع الإجازات</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            $leaveTypes = array_count_values(array_column($leaves, 'type'));
            $colors = [
                'annual' => 'bg-blue-100 text-blue-800',
                'sick' => 'bg-red-100 text-red-800',
                'emergency' => 'bg-orange-100 text-orange-800',
                'other' => 'bg-gray-100 text-gray-800'
            ];
            ?>
            <?php foreach ($leaveTypes as $type => $count): ?>
            <div class="text-center p-4 rounded-lg <?= $colors[$type] ?? 'bg-gray-100' ?>">
                <div class="text-2xl font-bold"><?= $count ?></div>
                <div class="text-sm"><?= $type ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>