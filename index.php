<?php
require_once 'db.php';
$page_title = 'الرئيسية - الجامعة الافتراضية';

// جلب فعالية نشطة واحدة لعرضها في الصفحة الرئيسية
$stmt = $pdo->query("SELECT * FROM events WHERE is_active = 1 ORDER BY event_date ASC LIMIT 1");
$active_event = $stmt->fetch();

// إذا كان المستخدم مسجّل دخول: جلب سجل الفعاليات التي سجّل بها
$my_registrations = [];
if (!empty($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("
        SELECT e.*, r.registered_at
        FROM registrations r
        JOIN events e ON e.id = r.event_id
        WHERE r.user_id = ?
        ORDER BY r.registered_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $my_registrations = $stmt->fetchAll();
}

include 'includes/header.php';
?>

<div class="container">

    <!-- الفعالية النشطة الحالية -->
    <?php if ($active_event): ?>
    <section class="hero-active-event mb-5">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <span class="badge bg-danger mb-2">فعالية نشطة الآن</span>
                <h1 class="fw-bold"><?= htmlspecialchars($active_event['title']) ?></h1>
                <p class="text-muted">
                    <i class="bi bi-calendar-event"></i>
                    <?= date('Y/m/d - H:i', strtotime($active_event['event_date'])) ?>
                    &nbsp;|&nbsp;
                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($active_event['location']) ?>
                </p>
                <p><?= htmlspecialchars(mb_substr($active_event['description'], 0, 160)) ?>...</p>
                <a href="event.php?id=<?= $active_event['id'] ?>" class="btn btn-primary btn-lg">
                    عرض التفاصيل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="col-md-6">
                <img src="assets/img/<?= htmlspecialchars($active_event['image']) ?>"
                     onerror="this.src='https://placehold.co/600x400?text=Event'"
                     class="img-fluid rounded-4 shadow" alt="<?= htmlspecialchars($active_event['title']) ?>">
            </div>
        </div>
    </section>
    <?php else: ?>
        <div class="alert alert-info">لا توجد فعالية نشطة حالياً، تابع صفحة <a href="events.php">جميع الفعاليات</a>.</div>
    <?php endif; ?>

    <!-- سجل الفعاليات المسجّل بها المستخدم -->
    <?php if (!empty($_SESSION['user_id'])): ?>
    <section class="my-events mb-5">
        <h3 class="fw-bold mb-3"><i class="bi bi-bookmark-check"></i> سجل تسجيلاتي بالفعاليات</h3>

        <?php if (count($my_registrations) === 0): ?>
            <p class="text-muted">لم تسجّل بأي فعالية بعد. تصفح <a href="events.php">الفعاليات</a> وسجّل بما يناسبك.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>الفعالية</th>
                            <th>التاريخ</th>
                            <th>المكان</th>
                            <th>تاريخ التسجيل</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($my_registrations as $reg): ?>
                        <tr>
                            <td><a href="event.php?id=<?= $reg['id'] ?>"><?= htmlspecialchars($reg['title']) ?></a></td>
                            <td><?= date('Y/m/d', strtotime($reg['event_date'])) ?></td>
                            <td><?= htmlspecialchars($reg['location']) ?></td>
                            <td><?= date('Y/m/d', strtotime($reg['registered_at'])) ?></td>
                            <td>
                                <form action="auth.php" method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="unregister_event">
                                    <input type="hidden" name="event_id" value="<?= $reg['id'] ?>">
                                    <input type="hidden" name="redirect" value="index.php">
                                    <button class="btn btn-sm btn-outline-danger">إلغاء التسجيل</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- تذييل حقوق النشر الخاص بصفحة المستخدم -->
    <section class="text-center text-muted small border-top pt-3">
        <p><i class="bi bi-c-circle"></i> جميع حقوق المحتوى المعروض محفوظة للجامعة الافتراضية، ولا يجوز إعادة النشر دون إذن.</p>
    </section>

</div>

<?php include 'includes/footer.php'; ?>
