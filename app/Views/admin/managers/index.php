<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">إدارة المدراء</h2>

    <?php if (!empty($managers)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-right">الاسم</th>
                        <th class="py-3 px-6 text-right">القسم</th>
                        <th class="py-3 px-6 text-right">المنصب</th>
                        <th class="py-3 px-6 text-right">عدد التقييمات</th>
                        <th class="py-3 px-6 text-right">متوسط التقييم</th>
                        <th class="py-3 px-6 text-right">عدد المهام</th>
                        <th class="py-3 px-6 text-right">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    <?php foreach ($managers as $manager): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6">
                                <div class="flex items-center">
                                    <span class="font-medium"><?= htmlspecialchars($manager['name']) ?></span>
                                </div>
                            </td>
                            <td class="py-3 px-6"><?= htmlspecialchars($manager['department']) ?></td>
                            <td class="py-3 px-6"><?= htmlspecialchars($manager['position']) ?></td>
                            <td class="py-3 px-6"><?= $manager['evaluation_count'] ?></td>
                            <td class="py-3 px-6">
                                <?php if ($manager['evaluation_count'] > 0): ?>
                                    <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs">
                                        <?= number_format($manager['avg_evaluation_score'], 1) ?> / 5
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-500">لا يوجد</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6"><?= $manager['task_count'] ?></td>
                            <td class="py-3 px-6">
                                <div class="flex item-center justify-end space-x-2">
                                    <!-- رابط إضافة تقييم -->
                                    <a href="/employee-portal/public/admin/managers/evaluate/<?= $manager['id'] ?>" 
                                       class="text-green-600 hover:text-green-900">
                                        <span class="material-icons text-sm">star</span>
                                        تقييم
                                    </a>
                                    
                                    <!-- رابط إضافة مهمة -->
                                    <a href="/employee-portal/public/admin/managers/task/<?= $manager['id'] ?>" 
                                       class="text-purple-600 hover:text-purple-900">
                                        <span class="material-icons text-sm">assignment</span>
                                        مهمة
                                    </a>
                                    
                                    <!-- رابط التفاصيل -->
                                    <a href="/employee-portal/public/admin/employees/record/<?= $manager['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <span class="material-icons text-sm">visibility</span>
                                        التفاصيل
                                    </a>

                                    <a href="/employee-portal/public/admin/managers/task/<?= $manager['id'] ?>" 
                                           class="text-purple-600 hover:text-purple-900">
                                            <span class="material-icons text-sm">assignment</span>
                                            المهام (<?= $manager['task_count'] ?>)
                                        </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <span class="material-icons text-4xl mb-3">people_outline</span>
            <p>لا يوجد مدراء مسجلين في النظام</p>
        </div>
    <?php endif; ?>
</div>