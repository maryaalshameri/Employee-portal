<h1>تعديل بيانات الموظف</h1>
<form method="post" action="/employee-portal/public/employee/edit?id=<?= $employee['id'] ?>">
    <label>معرّف المستخدم:</label><br>
    <input type="number" name="user_id" value="<?= htmlspecialchars($employee['user_id']) ?>" required><br><br>

    <label>القسم:</label><br>
    <input type="text" name="department" value="<?= htmlspecialchars($employee['department']) ?>" required><br><br>

    <label>الوظيفة:</label><br>
    <input type="text" name="position" value="<?= htmlspecialchars($employee['position']) ?>" required><br><br>

    <label>تاريخ التعيين:</label><br>
    <input type="date" name="hire_date" value="<?= htmlspecialchars($employee['hire_date']) ?>" required><br><br>

    <label>الراتب:</label><br>
    <input type="number" step="0.01" name="salary" value="<?= htmlspecialchars($employee['salary']) ?>" required><br><br>

    <label>الهاتف:</label><br>
    <input type="text" name="phone" value="<?= htmlspecialchars($employee['phone']) ?>"><br><br>

    <label>العنوان:</label><br>
    <textarea name="address"><?= htmlspecialchars($employee['address']) ?></textarea><br><br>

    <button type="submit">تحديث</button>
</form>
<a href="/employee-portal/public/employee">العودة للقائمة</a>
