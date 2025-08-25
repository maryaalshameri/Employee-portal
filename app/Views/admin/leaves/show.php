<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">تفاصيل طلب الإجازة</h2>
    
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <h3 class="font-semibold">الموظف:</h3>
            <p><?= htmlspecialchars($leave['employee_name']) ?></p>
        </div>
        <div>
            <h3 class="font-semibold">نوع الإجازة:</h3>
            <p>
                <?= $leave['type'] === 'annual' ? 'سنوية' : 
                    ($leave['type'] === 'sick' ? 'مرضية' : 
                    ($leave['type'] === 'emergency' ? 'طارئة' : 'أخرى')) ?>
            </p>
        </div>
        <div>
            <h3 class="font-semibold">من:</h3>
            <p><?= htmlspecialchars($leave['start_date']) ?></p>
        </div>
        <div>
            <h3 class="font-semibold">إلى:</h3>
            <p><?= htmlspecialchars($leave['end_date']) ?></p>
        </div>
        <div>
            <h3 class="font-semibold">المدة:</h3>
            <p><?= htmlspecialchars($leave['days_requested']) ?> يوم</p>
        </div>
        <div>
            <h3 class="font-semibold">الحالة:</h3>
            <span class="<?= $leave['status'] === 'approved' ? 'text-green-600' : 
                        ($leave['status'] === 'rejected' ? 'text-red-600' : 'text-yellow-600') ?> font-semibold">
                <?= $leave['status'] === 'approved' ? 'موافق' : 
                    ($leave['status'] === 'rejected' ? 'مرفوض' : 'قيد الانتظار') ?>
            </span>
        </div>
    </div>
    
    <div class="mb-6">
        <h3 class="font-semibold">السبب:</h3>
        <p class="bg-gray-100 p-4 rounded"><?= nl2br(htmlspecialchars($leave['reason'])) ?></p>
    </div>
    
    <?php if ($leave['status'] === 'approved' && !empty($leave['approved_by_name'])): ?>
    <div class="mb-6 bg-green-50 p-4 rounded">
        <h3 class="font-semibold text-green-800">معلومات الموافقة:</h3>
        <p class="text-green-700">
            <strong>وافق عليها:</strong> <?= htmlspecialchars($leave['approved_by_name']) ?>
        </p>
        <?php if (!empty($leave['comments'])): ?>
        <p class="text-green-700 mt-2">
            <strong>ملاحظات:</strong> <?= nl2br(htmlspecialchars($leave['comments'])) ?>
        </p>
        <?php endif; ?>
        <?php if (!empty($leave['updated_at'])): ?>
        <p class="text-green-700 mt-2">
            <strong>تاريخ الموافقة:</strong> <?= htmlspecialchars($leave['updated_at']) ?>
        </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($leave['status'] === 'rejected' && !empty($leave['approved_by_name'])): ?>
    <div class="mb-6 bg-red-50 p-4 rounded">
        <h3 class="font-semibold text-red-800">معلومات الرفض:</h3>
        <p class="text-red-700">
            <strong>رفضها:</strong> <?= htmlspecialchars($leave['approved_by_name']) ?>
        </p>
        <?php if (!empty($leave['comments'])): ?>
        <p class="text-red-700 mt-2">
            <strong>سبب الرفض:</strong> <?= nl2br(htmlspecialchars($leave['comments'])) ?>
        </p>
        <?php endif; ?>
        <?php if (!empty($leave['updated_at'])): ?>
        <p class="text-red-700 mt-2">
            <strong>تاريخ الرفض:</strong> <?= htmlspecialchars($leave['updated_at']) ?>
        </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($leave['status'] === 'pending'): ?>
    <div class="flex flex-col space-x-4 gap-5 md:flex-row">
        <form action="/employee-portal/public/admin/leaves/approve/<?= $leave['id'] ?>" method="POST" class="flex-1">
            <div class="mb-3">
                <label for="approve-comments" class="block text-sm font-medium text-gray-700">ملاحظات الموافقة (اختياري)</label>
                <textarea name="comments" id="approve-comments" rows="3" 
                          class="border p-2 rounded w-full" placeholder="ملاحظات الموافقة..."></textarea>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded w-full">
                ✓ موافقة
            </button>
        </form>
        
        <form action="/employee-portal/public/admin/leaves/reject/<?= $leave['id'] ?>" method="POST" class="flex-1">
            <div class="mb-3">
                <label for="reject-comments" class="block text-sm font-medium text-gray-700">سبب الرفض (اختياري)</label>
                <textarea name="comments" id="reject-comments" rows="3" 
                          class="border p-2 rounded w-full" placeholder="سبب الرفض..."></textarea>
            </div>
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-full">
                ✗ رفض
            </button>
        </form>
    </div>
    <?php endif; ?>
    
    <div class="mt-6">
        <a href="/employee-portal/public/admin/leaves" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
            ← رجوع إلى القائمة
        </a>
        
        <?php if ($leave['status'] === 'approved' || $leave['status'] === 'rejected'): ?>
        <a href="/employee-portal/public/admin/leaves/my-approvals" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded ml-2">
            الإجازات التي وافقت عليها
        </a>
        <?php endif; ?>
    </div>
</div>