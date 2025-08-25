<div class="container-fluid">
    <!-- رسائل التنبيه -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <h1 class="text-3xl font-bold mb-6">
        مرحبًا <?= htmlspecialchars($employee['name']) ?> 
        <span class="text-gray-500 text-lg">(<?= htmlspecialchars($employee['role']) ?>)</span>
    </h1>
    
    <div class="row">
        <!-- البطاقات الإحصائية -->
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">رصيد الإجازات</h5>
                    <h2 class="card-text"><?= $stats['available_balance'] ?> يوم</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">الإجازات المعتمدة</h5>
                    <h2 class="card-text"><?= $stats['approved_leaves'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">طلبات قيد المراجعة</h5>
                    <h2 class="card-text"><?= $stats['pending_leaves'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">إجمالي الإجازات</h5>
                    <h2 class="card-text"><?= $stats['total_leaves'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- الإجراءات السريعة -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>الإجراءات السريعة</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/employee-portal/public/employee/leave-request" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> طلب إجازة جديدة
                        </a>
                        <a href="/employee-portal/public/employee/tasks" class="btn btn-info">
                            <i class="fas fa-tasks"></i> عرض المهام
                        </a>
                        <a href="/employee-portal/public/employee/profile" class="btn btn-secondary">
                            <i class="fas fa-user"></i> الملف الشخصي
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإشعارات الحديثة -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>آخر الإشعارات</h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <?php 
                    $hasNotifications = false;
                    
                    // إشعارات الإجازات
                    if (!empty($pendingLeaves)): ?>
                        <?php foreach ($pendingLeaves as $leave): ?>
                            <div class="alert alert-warning mb-2 p-2">
                                <i class="fas fa-calendar-clock"></i>
                                طلب إجازة قيد المراجعة (<?= $leave['days_requested'] ?> يوم)
                                <br>
                                <small class="text-muted">من <?= $leave['start_date'] ?> إلى <?= $leave['end_date'] ?></small>
                            </div>
                            <?php $hasNotifications = true; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- إشعارات المهام -->
                    <?php if (!empty($recentTasks)): ?>
                        <?php foreach ($recentTasks as $task): ?>
                            <?php 
                            $taskStatus = [
                                'todo' => 'secondary',
                                'in_progress' => 'primary',
                                'done' => 'success',
                                'blocked' => 'danger'
                            ];
                            ?>
                            <div class="alert alert-<?= $taskStatus[$task['status']] ?> mb-2 p-2">
                                <i class="fas fa-tasks"></i>
                                <?php if ($task['status'] === 'todo'): ?>
                                    مهمة جديدة: <?= htmlspecialchars($task['title']) ?>
                                <?php elseif ($task['status'] === 'in_progress'): ?>
                                    مهمة قيد التنفيذ: <?= htmlspecialchars($task['title']) ?>
                                <?php elseif ($task['status'] === 'done'): ?>
                                    تم إكمال المهمة: <?= htmlspecialchars($task['title']) ?>
                                <?php else: ?>
                                    مهمة متوقفة: <?= htmlspecialchars($task['title']) ?>
                                <?php endif; ?>
                                
                                <?php if ($task['due_date']): ?>
                                    <br>
                                    <small class="text-muted">
                                        موعد التسليم: <?= $task['due_date'] ?>
                                        <?php if (strtotime($task['due_date']) < strtotime('+3 days')): ?>
                                            <span class="text-danger">!قريب</span>
                                        <?php endif; ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <?php $hasNotifications = true; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- إشعارات الرواتب -->
                    <?php if (!empty($salaryNotifications)): ?>
                        <?php foreach ($salaryNotifications as $salary): ?>
                            <div class="alert alert-<?= $salary['status'] === 'approved' ? 'success' : ($salary['status'] === 'rejected' ? 'danger' : 'warning') ?> mb-2 p-2">
                                <i class="fas fa-money-bill-wave"></i>
                                <?php if ($salary['status'] === 'pending'): ?>
                                    طلب راتب قيد المراجعة
                                <?php elseif ($salary['status'] === 'approved'): ?>
                                    تم اعتماد الراتب: <?= number_format($salary['amount'], 2) ?> ر.ي
                                <?php else: ?>
                                    تم رفض طلب الراتب
                                <?php endif; ?>
                                
                                <?php if ($salary['status'] === 'approved' && $salary['payment_date']): ?>
                                    <br>
                                    <small class="text-muted">تاريخ الصرف: <?= $salary['payment_date'] ?></small>
                                <?php endif; ?>
                            </div>
                            <?php $hasNotifications = true; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                       <?php if (!empty($evaluationNotifications)): ?>
                <?php foreach ($evaluationNotifications as $evaluation): ?>
                    <div class="alert alert-info mb-2">
                        <i class="fas fa-chart-line"></i>
                        <?php 
                        $avgScore = ($evaluation['performance_score'] + $evaluation['quality_score'] + 
                                   $evaluation['punctuality_score'] + $evaluation['teamwork_score']) / 4;
                        ?>
                        تقييم جديد: <?= number_format($avgScore, 1) ?>/5
                        <br>
                        <small class="text-muted">بتاريخ: <?= $evaluation['evaluation_date'] ?></small>
                    </div>
                    <?php $hasNotifications = true; ?>
                <?php endforeach; ?>
            <?php endif; ?>

                    <?php if (!$hasNotifications): ?>
                        <p class="text-muted text-center py-3">لا توجد إشعارات حالياً</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
     
    <div class="row mt-4">
        <!-- آخر المهام -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>آخر المهام</h5>
                    <a href="/employee-portal/public/employee/tasks" class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentTasks)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>المهمة</th>
                                        <th>الحالة</th>
                                        <th>الاستحقاق</th>
                                        <th>الأولوية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentTasks as $task): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($task['title']) ?></strong>
                                                <?php if ($task['description']): ?>
                                                    <br>
                                                    <small class="text-muted"><?= htmlspecialchars(substr($task['description'], 0, 50)) ?>...</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= 
                                                    $task['status'] === 'done' ? 'success' : 
                                                    ($task['status'] === 'in_progress' ? 'primary' : 'secondary')
                                                ?>">
                                                    <?= $task['status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($task['due_date']): ?>
                                                    <?= $task['due_date'] ?>
                                                    <?php if (strtotime($task['due_date']) < strtotime('+3 days')): ?>
                                                        <br>
                                                        <span class="badge bg-danger">قريب!</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">غير محدد</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= 
                                                    $task['priority'] === 'high' ? 'danger' : 
                                                    ($task['priority'] === 'medium' ? 'warning' : 'info')
                                                ?>">
                                                    <?= $task['priority'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">لا توجد مهام حالياً</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- آخر الرواتب -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>آخر الرواتب</h5>
                    <a href="/employee-portal/public/employee/salaries" class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($salaryNotifications)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الدفع</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($salaryNotifications as $salary): ?>
                                        <?php
                                        $netSalary = $salary['amount'] + 
                                                   ($salary['amount'] * $salary['bonusPercentage'] / 100) - 
                                                   ($salary['amount'] * $salary['deductionPercentage'] / 100);
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?= number_format($netSalary, 2) ?> ر.ي</strong>
                                                <br>
                                                <small class="text-muted">
                                                    أساسي: <?= number_format($salary['amount'], 2) ?> ر.ي
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= 
                                                    $salary['status'] === 'approved' ? 'success' : 
                                                    ($salary['status'] === 'rejected' ? 'danger' : 'warning')
                                                ?>">
                                                    <?= $salary['status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($salary['payment_date']): ?>
                                                    <?= $salary['payment_date'] ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= date('Y-m-d', strtotime($salary['created_at'])) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">لا توجد سجلات رواتب</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>