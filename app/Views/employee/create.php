<h1>إضافة موظف جديد</h1>
<form method="post" action="/employee-portal/public/employee/create">
    <label>معرّف المستخدم:</label><br>
    <input type="number" name="user_id" required><br><br>

    <label>القسم:</label><br>
    <input type="text" name="department" required><br><br>

    <label>الوظيفة:</label><br>
    <input type="text" name="position" required><br><br>

    <label>تاريخ التعيين:</label><br>
    <input type="date" name="hire_date" required><br><br>

    <label>الراتب:</label><br>
    <input type="number" step="0.01" name="salary" required><br><br>

    <label>الهاتف:</label><br>
    <input type="text" name="phone"><br><br>

    <label>العنوان:</label><br>
    <textarea name="address"></textarea><br><br>

    <button type="submit">إضافة</button>
</form>
<a href="/employee-portal/public/employee">العودة للقائمة</a>
