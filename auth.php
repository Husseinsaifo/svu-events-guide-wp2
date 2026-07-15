<?php
require_once 'db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'index.php';

function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

switch ($action) {

    // ------------------------------------------------------
    // تسجيل الدخول
    // ------------------------------------------------------
    case 'login':
        $username  = trim($_POST['username'] ?? '');
        $password  = $_POST['password'] ?? '';
        $user_type = $_POST['user_type'] ?? '';

        if ($username === '' || $password === '') {
            set_flash('danger', 'الرجاء إدخال اسم المستخدم وكلمة المرور');
            break;
        }

        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['is_admin']  = (bool) $user['is_admin'];
            set_flash('success', 'تم تسجيل الدخول بنجاح، أهلاً بك ' . $user['username']);
        } else {
            set_flash('danger', 'اسم المستخدم أو كلمة المرور غير صحيحة');
        }
        break;

    // ------------------------------------------------------
    // تسجيل الخروج
    // ------------------------------------------------------
    case 'logout':
        session_unset();
        session_destroy();
        session_start();
        set_flash('success', 'تم تسجيل الخروج بنجاح');
        break;

    // ------------------------------------------------------
    // التسجيل في فعالية
    // ------------------------------------------------------
    case 'register_event':
        if (empty($_SESSION['user_id'])) {
            set_flash('warning', 'يجب تسجيل الدخول أولاً للتسجيل في الفعالية');
            break;
        }
        $event_id = (int) ($_POST['event_id'] ?? 0);
        try {
            $stmt = $pdo->prepare('INSERT INTO registrations (user_id, event_id) VALUES (?, ?)');
            $stmt->execute([$_SESSION['user_id'], $event_id]);
            set_flash('success', 'تم تسجيلك في الفعالية بنجاح');
        } catch (PDOException $e) {
            set_flash('info', 'أنت مسجل بالفعل في هذه الفعالية');
        }
        break;

    // ------------------------------------------------------
    // إلغاء التسجيل في فعالية
    // ------------------------------------------------------
    case 'unregister_event':
        if (empty($_SESSION['user_id'])) {
            set_flash('warning', 'يجب تسجيل الدخول أولاً');
            break;
        }
        $event_id = (int) ($_POST['event_id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM registrations WHERE user_id = ? AND event_id = ?');
        $stmt->execute([$_SESSION['user_id'], $event_id]);
        set_flash('success', 'تم إلغاء تسجيلك من الفعالية');
        break;

    default:
        set_flash('danger', 'إجراء غير معروف');
}

header('Location: ' . $redirect);
exit;
