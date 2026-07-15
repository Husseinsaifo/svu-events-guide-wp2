<?php
/**
 * admin_auth.php
 * يُستدعى في بداية كل صفحة إدارية لضمان أن الداخل هو مشرف فعلاً
 */
require_once __DIR__ . '/../db.php';

function require_admin() {
    if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
        header('Location: login.php');
        exit;
    }
}
