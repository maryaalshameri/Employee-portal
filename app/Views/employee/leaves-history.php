<div class="container-fluid">
    <h1 class="text-2xl font-bold mb-6">سجل الإجازات</h1>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">جميع طلبات الإجازة</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($leaves)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>نوع الإجازة</th>
                                <th>من تاريخ</th>
                                <th>إلى تاريخ</th>
                                <th>المدة</th>
                                <th>السبب</th>
                                <th>الحالة</th>
                                <th>تاريخ الطلب</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leaves as $leave): ?>
                                <tr>
                                    <td>
                                        <?= 
                                            $leave['type'] === 'annual' ? 'سنوية' : 
                                            ($leave['type'] === 'sick' ? 'مرضية' : 
                                            ($leave['type'] === 'emergency' ? 'طارئة' : 'أخرى'))
                                        ?>
                                    </td>
                                    <td><?= $leave['start_date'] ?></td>
                                    <td><?= $leave['end_date'] ?></td>
                                    <td><?= $leave['days_requested'] ?> يوم</td>
                                    <td><?= htmlspecialchars($leave['reason']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $leave['status'] === 'approved' ? 'success' : 
                                            ($leave['status'] === 'rejected' ? 'danger' : 'warning')
                                        ?>">
                                            <?= $leave['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($leave['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">لا توجد طلبات إجازة سابقة</p>
            <?php endif; ?>
            
            <div class="mt-3">
                <a href="/employee-portal/public/employee" class="btn btn-primary">رجوع إلى الرئيسية</a>
            </div>
        </div>
    </div>
</div>