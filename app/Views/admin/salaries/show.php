<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">تفاصيل الراتب</h2>

    <!-- رسائل النجاح/الخطأ -->
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

    <!-- زر الرجوع -->
    <div class="mb-4">
        <a href="/employee-portal/public/admin/salaries"
           class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded inline-flex items-center">
            <i class="fas fa-arrow-right ml-2"></i> رجوع إلى قائمة الرواتب
        </a>
    </div>

    <?php if (!empty($salary)): ?>
        <?php
        $netSalary = $salary['amount']
            + ($salary['amount'] * $salary['bonusPercentage'] / 100)
            - ($salary['amount'] * $salary['deductionPercentage'] / 100);
        ?>

        <!-- بطاقة تفاصيل الراتب -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- المعلومات الأساسية -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">المعلومات الأساسية</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">الموظف:</span>
                        <span class="font-medium"><?= htmlspecialchars($salary['employee_name']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">البريد الإلكتروني:</span>
                        <span class="font-medium"><?= htmlspecialchars($salary['employee_email'] ?? 'غير متوفر') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">المبلغ الأساسي:</span>
                        <span class="font-medium"><?= number_format($salary['amount'], 2) ?> ﷼</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">نسبة المكافأة:</span>
                        <span class="font-medium"><?= number_format($salary['bonusPercentage'], 2) ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">نسبة الخصم:</span>
                        <span class="font-medium"><?= number_format($salary['deductionPercentage'], 2) ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">المبلغ الصافي:</span>
                        <span class="font-bold text-green-600"><?= number_format($netSalary, 2) ?> ﷼</span>
                    </div>
                </div>
            </div>

            <!-- معلومات الحالة والتواريخ -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">معلومات الحالة</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">الحالة:</span>
                        <span class="<?= $salary['status'] === 'approved' ? 'text-green-600' : 
                                    ($salary['status'] === 'rejected' ? 'text-red-600' : 'text-yellow-600') ?> font-semibold">
                            <?= $salary['status'] === 'approved' ? 'معتمدة' : 
                                ($salary['status'] === 'rejected' ? 'مرفوضة' : 'قيد الانتظار') ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">تاريخ الدفع:</span>
                        <span class="font-medium"><?= $salary['payment_date'] ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">تاريخ الإنشاء:</span>
                        <span class="font-medium"><?= $salary['created_at'] ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">تاريخ آخر تعديل:</span>
                        <span class="font-medium"><?= $salary['updated_at'] ?></span>
                    </div>
                  <?php if ($salary['status'] !== 'pending' && !empty($salary['approved_by_name'])): ?>
    <div class="flex justify-between">
        <span class="text-gray-600">تمت الموافقة/الرفض بواسطة:</span>
        <span class="font-medium"><?= htmlspecialchars($salary['approved_by_name']) ?></span>
    </div>
<?php endif; ?>
                </div>
            </div>
        </div>

        <!-- الملاحظات والتعليقات -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">الملاحظات والتعليقات</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 block mb-2">ملاحظات الراتب:</span>
                    <p class="bg-white p-3 rounded border"><?= !empty($salary['comments']) ? htmlspecialchars($salary['comments']) : 'لا توجد ملاحظات' ?></p>
                </div>
                <?php if (!empty($salary['comments'])): ?>
                    <div>
                        <span class="text-gray-600 block mb-2">تعليقات الموافقة/الرفض:</span>
                        <p class="bg-white p-3 rounded border"><?= htmlspecialchars($salary['comments']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="flex space-x-4">
            <?php if ($salary['status'] === 'pending'): ?>
                <form action="/employee-portal/public/admin/salaries/approve/<?= $salary['id'] ?>" method="POST" class="inline">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center">
                        <i class="fas fa-check ml-2"></i> موافقة
                    </button>
                </form>
                <form action="/employee-portal/public/admin/salaries/reject/<?= $salary['id'] ?>" method="POST" class="inline">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center">
                        <i class="fas fa-times ml-2"></i> رفض
                    </button>
                </form>
            <?php endif; ?>
            
            <a href="/employee-portal/public/admin/salaries/edit/<?= $salary['id'] ?>" 
               class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center">
                <i class="fas fa-edit ml-2"></i> تعديل
            </a>
            
            <form action="/employee-portal/public/admin/salaries/delete/<?= $salary['id'] ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الراتب؟')">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center">
                    <i class="fas fa-trash ml-2"></i> حذف
                </button>
            </form>
        </div>

    <?php else: ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <p>الراتب غير موجود أو تم حذفه.</p>
        </div>
    <?php endif; ?>
</div>