<?php
/**
 * db.php
 * ملف الاتصال الموحّد بقاعدة البيانات MySQL باستخدام PDO
 * يتم استدعاؤه من جميع صفحات المشروع (الأمامية والخلفية)
 */

$db_host = 'localhost';
$db_name = 'city_events';
$db_user = 'root';      // عدّل حسب إعدادات جهازك في XAMPP/Laragon
$db_pass = '';          // عدّل حسب إعدادات جهازك

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
}

// بدء الجلسة في مكان واحد موحّد لكل المشروع
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
