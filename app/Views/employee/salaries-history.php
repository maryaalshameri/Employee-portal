<div class="container-fluid">
    <h1 class="text-2xl font-bold mb-6">سجل الرواتب</h1>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">جميع طلبات الرواتب</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($salaries)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>المبلغ</th>
                                <th>نسبة المكافأة</th>
                                <th>نسبة الخصم</th>
                                <th>الصافي</th>
                                <th>تاريخ الدفع</th>
                                <th>الحالة</th>
                                <th>تاريخ الطلب</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($salaries as $salary): ?>
                                <?php
                                $netSalary = $salary['amount'] + 
                                           ($salary['amount'] * $salary['bonusPercentage'] / 100) - 
                                           ($salary['amount'] * $salary['deductionPercentage'] / 100);
                                ?>
                                <tr>
                                    <td><?= number_format($salary['amount'], 2) ?> ر.ي</td>
                                    <td><?= $salary['bonusPercentage'] ?>%</td>
                                    <td><?= $salary['deductionPercentage'] ?>%</td>
                                    <td><strong><?= number_format($netSalary, 2) ?> ر.ي</strong></td>
                                    <td><?= $salary['payment_date'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $salary['status'] === 'approved' ? 'success' : 
                                            ($salary['status'] === 'rejected' ? 'danger' : 'warning')
                                        ?>">
                                            <?= $salary['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($salary['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">لا توجد طلبات رواتب سابقة</p>
            <?php endif; ?>
            
            <div class="mt-3">
                <a href="/employee-portal/public/employee" class="btn btn-primary">رجوع إلى الرئيسية</a>
            </div>
        </div>
    </div>
</div>