<?php
// صفحة الموظفين
?>
<h1>قائمة الموظفين</h1>
<a href="/employee-portal/public/employee/create">إضافة موظف جديد</a>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>الاسم</th>
            <th>البريد</th>
            <th>الدور</th>
            <th>القسم</th>
            <th>الوظيفة</th>
            <th>تاريخ التعيين</th>
            <th>الراتب</th>
            <th>الهاتف</th>
            <th>العنوان</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employees as $emp): ?>
        <tr>
            <td><?= htmlspecialchars($emp['name']) ?></td>
            <td><?= htmlspecialchars($emp['email']) ?></td>
            <td><?= htmlspecialchars($emp['role']) ?></td>
            <td><?= htmlspecialchars($emp['department']) ?></td>
            <td><?= htmlspecialchars($emp['position']) ?></td>
            <td><?= htmlspecialchars($emp['hire_date']) ?></td>
            <td><?= htmlspecialchars($emp['salary']) ?></td>
            <td><?= htmlspecialchars($emp['phone']) ?></td>
            <td><?= htmlspecialchars($emp['address']) ?></td>
            <td>
                <a href="/employee-portal/public/employee/edit?id=<?= $emp['id'] ?>">تعديل</a> |
                <a href="/employee-portal/public/employee/delete?id=<?= $emp['id'] ?>" onclick="return confirm('هل تريد حذف هذا الموظف؟')">حذف</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/employee-portal/public/">العودة للرئيسية</a>
