<?php
// متغيرات افتراضية لتجنب الأخطاء
$leaves = $leaves ?? [];
$trashCount = $trashCount ?? 0;
?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b-2 border-red-200 pb-3">
        <span class="material-icons text-red-500 align-middle mr-2">delete</span>
        سلة محذوفات الإجازات
    </h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            <div class="flex items-center">
                <span class="material-icons text-green-600 mr-2">check_circle</span>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <div class="flex items-center">
                <span class="material-icons text-red-600 mr-2">error</span>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
        <div class="flex items-center">
            <span class="material-icons text-red-500 mr-2">info</span>
            <div>
                <h3 class="font-semibold text-red-800">سلة المحذوفات</h3>
                <p class="text-red-700">تحتوي على <?= count($leaves) ?> طلب إجازة محذوف. يمكنك الاستعادة أو الحذف النهائي.</p>
            </div>
        </div>
    </div>
    
    <div class="mb-6 flex flex-wrap gap-3">
        <a href="/employee-portal/public/admin/leaves" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
            <span class="material-icons mr-2">arrow_back</span>
            رجوع إلى الإجازات
        </a>
        
        <?php if ($trashCount > 0): ?>
            <form action="/employee-portal/public/admin/leaves/empty-trash" method="POST" class="inline">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
                        onclick="return confirm('هل أنت متأكد من تفريغ سلة المحذوفات؟ هذا الإجراء لا يمكن التراجع عنه!')">
                    <span class="material-icons mr-2">delete_forever</span>
                    تفريغ السلة
                </button>
            </form>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($leaves)): ?>
        <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الموظف</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نوع الإجازة</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الفترة</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">المدة</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">تاريخ الحذف</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($leaves as $leave): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($leave['employee_name'] ?? 'غير معروف') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $leave['type'] === 'annual' ? 'سنوية' : 
                                    ($leave['type'] === 'sick' ? 'مرضية' : 
                                    ($leave['type'] === 'emergency' ? 'طارئة' : 'أخرى')) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($leave['start_date'] ?? '') ?> - <?= htmlspecialchars($leave['end_date'] ?? '') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($leave['days_requested'] ?? 0) ?> يوم
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    <?= $leave['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                        ($leave['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                    <?= $leave['status'] === 'approved' ? 'موافق' : 
                                        ($leave['status'] === 'rejected' ? 'مرفوض' : 'قيد الانتظار') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500">
                                <?= htmlspecialchars($leave['deleted_at'] ?? 'غير محدد') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <form action="/employee-portal/public/admin/leaves/restore/<?= $leave['id'] ?>" method="POST" class="inline">
                                        <button type="submit" class="text-green-600 hover:text-green-900 transition-colors"
                                                title="استعادة"
                                                onclick="return confirm('هل تريد استعادة هذا الطلب؟')">
                                            <span class="material-icons text-base">restore</span>
                                        </button>
                                    </form>
                                    
                                    <form action="/employee-portal/public/admin/leaves/delete-final/<?= $leave['id'] ?>" method="POST" class="inline">
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors"
                                                title="حذف نهائي"
                                                onclick="return confirm('هل أنت متأكد من الحذف النهائي؟ هذا الإجراء لا يمكن التراجع عنه!')">
                                            <span class="material-icons text-base">delete_forever</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-sm text-gray-500">
            <span class="material-icons align-text-bottom text-xs">info</span>
            إجمالي <?= count($leaves) ?> طلب إجازة في سلة المحذوفات
        </div>
        
    <?php else: ?>
        <div class="bg-gray-50 border-l-4 border-gray-400 p-6 rounded text-center">
            <span class="material-icons text-gray-400 text-5xl mb-3">delete_sweep</span>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">سلة المحذوفات فارغة</h3>
            <p class="text-gray-500">لا توجد طلبات إجازة محذوفة حالياً.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.material-icons {
    font-size: 18px;
    vertical-align: middle;
}
</style>