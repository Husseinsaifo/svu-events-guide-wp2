<?php
require_once __DIR__ . '/../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND is_admin = 1 LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id']   = $admin['id'];
        $_SESSION['username']  = $admin['username'];
        $_SESSION['user_type'] = $admin['user_type'];
        $_SESSION['is_admin']  = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'اسم المستخدم أو كلمة المرور غير صحيحة، أو لا تملك صلاحية الإشراف';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرف - لوحة التحكم</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/project/assets/css/styles.css">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh; background:linear-gradient(135deg,#1e2f8f,#2f4bcf);">
    <div class="card shadow-lg" style="width:100%; max-width:400px;">
        <div class="card-body p-4">
            <h4 class="fw-bold text-center mb-4"><i class="bi bi-shield-lock"></i> دخول لوحة التحكم</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">اسم المستخدم</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">دخول</button>
            </form>
            <p class="text-muted small text-center mt-3 mb-0">للتجربة: admin / admin123</p>
            <p class="text-center mt-2"><a href="../index.php" class="small">عودة للموقع الرئيسي</a></p>
        </div>
    </div>
</body>
</html>
