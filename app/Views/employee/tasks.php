<div class="container-fluid">
    <h1 class="text-2xl font-bold mb-6">المهام الموكلة إلي</h1>

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

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">قائمة المهام</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($tasks)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>عنوان المهمة</th>
                                <th>الوصف</th>
                                <th>تاريخ الاستحقاق</th>
                                <th>الأولوية</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?= htmlspecialchars($task['title']) ?></td>
                                    <td><?= htmlspecialchars($task['description'] ?? 'لا يوجد وصف') ?></td>
                                    <td><?= $task['due_date'] ?? 'غير محدد' ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $task['priority'] === 'high' ? 'danger' : 
                                            ($task['priority'] === 'medium' ? 'warning' : 'success')
                                        ?>">
                                            <?= $task['priority'] ?>
                                        </span>
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
                                        <form action="/employee-portal/public/employee/tasks/update-status/<?= $task['id'] ?>" method="POST" class="d-inline">
                                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                                <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>للعمل</option>
                                                <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>قيد التنفيذ</option>
                                                <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>مكتمل</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">لا توجد مهام موكلة إليك حالياً</p>
            <?php endif; ?>
        </div>
    </div>
</div>