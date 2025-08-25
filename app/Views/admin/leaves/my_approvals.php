<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">الإجازات التي وافقت عليها</h2>
    
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
    
    <div class="mb-4">
        <a href="/employee-portal/public/admin/leaves" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
            ← رجوع إلى جميع الإجازات
        </a>
    </div>
    
    <?php if (!empty($leaves)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">الموظف</th>
                        <th class="px-4 py-2 border">نوع الإجازة</th>
                        <th class="px-4 py-2 border">الفترة</th>
                        <th class="px-4 py-2 border">المدة</th>
                        <th class="px-4 py-2 border">الحالة</th>
                        <th class="px-4 py-2 border">تاريخ الموافقة</th>
                        <th class="px-4 py-2 border">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaves as $leave): ?>
                        <tr>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($leave['employee_name']) ?></td>
                            <td class="px-4 py-2 border">
                                <?= $leave['type'] === 'annual' ? 'سنوية' : 
                                    ($leave['type'] === 'sick' ? 'مرضية' : 
                                    ($leave['type'] === 'emergency' ? 'طارئة' : 'أخرى')) ?>
                            </td>
                            <td class="px-4 py-2 border">
                                <?= htmlspecialchars($leave['start_date']) ?> - <?= htmlspecialchars($leave['end_date']) ?>
                            </td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($leave['days_requested']) ?> يوم</td>
                            <td class="px-4 py-2 border">
                                <span class="<?= $leave['status'] === 'approved' ? 'text-green-600' : 'text-red-600' ?> font-semibold">
                                    <?= $leave['status'] === 'approved' ? 'موافق' : 'مرفوض' ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($leave['updated_at']) ?></td>
                            <td class="px-4 py-2 border">
                                <a href="/employee-portal/public/admin/leaves/show/<?= $leave['id'] ?>" 
                                   class="text-blue-500 hover:text-blue-700">عرض التفاصيل</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            لم توافق على أي إجازات حتى الآن.
        </div>
    <?php endif; ?>
</div>