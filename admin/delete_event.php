<?php
require_once 'admin_auth.php';
require_admin();

$event_id = (int) ($_GET['id'] ?? 0);

if ($event_id > 0) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'تم حذف الفعالية بنجاح'];
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'معرّف الفعالية غير صالح'];
}

header('Location: dashboard.php');
exit;
