<?php
// $page_title يجب أن يكون معرّفاً في كل صفحة قبل استدعاء الهيدر
if (!isset($page_title)) {
    $page_title = 'الجامعة الافتراضية - الفعاليات';
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>

    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- ملف التنسيق الخاص بالمشروع -->
    <link rel="stylesheet" href="/project/assets/css/styles.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark main-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-mortarboard-fill"></i> الجامعة الافتراضية
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>" href="index.php">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'events.php' ? 'active' : '' ?>" href="events.php">الفعاليات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'about.php' ? 'active' : '' ?>" href="about.php">عن الفعالية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'contact.php' ? 'active' : '' ?>" href="contact.php">اتصل بنا</a>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-lg-center me-lg-3">
                    <li class="nav-item">
                        <span id="darkModeToggle" class="nav-link" title="تبديل الوضع الليلي">
                            <i class="bi bi-moon-stars-fill"></i>
                        </span>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <li class="nav-item d-flex align-items-center gap-2">
                            <span class="navbar-text text-white-50">
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars($_SESSION['username']) ?>
                                <span class="badge bg-light text-dark"><?= htmlspecialchars($_SESSION['user_type']) ?></span>
                            </span>
                            <a href="auth.php?action=logout" class="btn btn-sm btn-outline-light">تسجيل الخروج</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <button class="btn btn-sm btn-light fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">
                                تسجيل الدخول
                            </button>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- تنبيهات النجاح / الخطأ القادمة من auth.php -->
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="container mt-3">
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type']) ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Modal تسجيل الدخول (متاح من كل الصفحات) -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="auth.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-box-arrow-in-left"></i> تسجيل الدخول</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($current_page) ?>">

                    <div class="mb-3">
                        <label class="form-label">اسم المستخدم</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نوع المستخدم</label>
                        <select name="user_type" class="form-select" required>
                            <option value="طالب">طالب</option>
                            <option value="موظف">موظف</option>
                            <option value="عضو هيئة تدريس">عضو هيئة تدريس</option>
                        </select>
                    </div>
                    <div class="form-text">للتجربة: admin / admin123 أو student1 / student123</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">دخول</button>
                </div>
            </form>
        </div>
    </div>
</div>

<main class="py-4">
