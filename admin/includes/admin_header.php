<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'لوحة التحكم') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/project/assets/css/styles.css">
</head>
<body>
<div class="d-flex">
    <!-- الشريط الجانبي -->
    <div class="admin-sidebar p-3" style="width:250px;">
        <h5 class="text-white fw-bold mb-4"><i class="bi bi-speedometer2"></i> لوحة التحكم</h5>
        <a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">
            <i class="bi bi-list-ul"></i> إدارة الفعاليات
        </a>
        <a href="add_event.php" class="<?= $current === 'add_event.php' ? 'active' : '' ?>">
            <i class="bi bi-plus-circle"></i> إضافة فعالية
        </a>
        <a href="../index.php" target="_blank">
            <i class="bi bi-box-arrow-up-left"></i> عرض الموقع
        </a>
        <a href="logout.php" class="mt-3 text-warning">
            <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
        </a>
    </div>

    <!-- المحتوى -->
    <div class="flex-grow-1 p-4">
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type']) ?> alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
