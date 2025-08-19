<?php
// لا داعي session_start() هنا
if (!empty($_SESSION['user'])) {
    header("Location: /employee-portal/public/{$_SESSION['user']['role']}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>تسجيل الدخول</title>
</head>
<body>
<h2>تسجيل الدخول</h2>
<?php if(!empty($_SESSION['error'])): ?>
    <p style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form method="post" action="login">
<label>البريد الإلكتروني</label><br>
<input type="email" name="email" required><br><br>

<label>كلمة المرور</label><br>
<input type="password" name="password" required><br><br>

<button type="submit">دخول</button>
</form>
</body>
</html>
