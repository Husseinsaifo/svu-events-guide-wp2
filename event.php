<?php
require_once 'db.php';

$event_id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    $page_title = 'الفعالية غير موجودة';
    include 'includes/header.php';
    echo '<div class="container"><div class="alert alert-danger">عذراً، الفعالية المطلوبة غير موجودة.
          <a href="events.php">عودة لقائمة الفعاليات</a></div></div>';
    include 'includes/footer.php';
    exit;
}

$page_title = $event['title'] . ' - الجامعة الافتراضية';

// هل المستخدم الحالي مسجّل بهذه الفعالية؟
$is_registered = false;
if (!empty($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT 1 FROM registrations WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$_SESSION['user_id'], $event_id]);
    $is_registered = (bool) $stmt->fetch();
}

// فعاليات ذات صلة (نفس التصنيف، باستثناء الفعالية الحالية)
$stmt = $pdo->prepare("SELECT * FROM events WHERE category = ? AND id != ? ORDER BY event_date ASC LIMIT 3");
$stmt->execute([$event['category'], $event_id]);
$related_events = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="events.php">الفعاليات</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($event['title']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-7">
            <img src="assets/img/<?= htmlspecialchars($event['image']) ?>"
                 onerror="this.src='https://placehold.co/700x400?text=Event'"
                 class="img-fluid rounded-4 shadow mb-3" alt="<?= htmlspecialchars($event['title']) ?>">
        </div>
        <div class="col-lg-5">
            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($event['category']) ?></span>
            <h1 class="fw-bold"><?= htmlspecialchars($event['title']) ?></h1>
            <p class="text-muted mb-1"><i class="bi bi-calendar-event"></i>
                <?= date('Y/m/d - H:i', strtotime($event['event_date'])) ?>
            </p>
            <p class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($event['location']) ?></p>

            <div class="d-flex flex-wrap gap-2 my-3">
                <a class="btn btn-outline-primary" id="addToCalendarBtn"
                   data-title="<?= htmlspecialchars($event['title']) ?>"
                   data-desc="<?= htmlspecialchars($event['description']) ?>"
                   data-location="<?= htmlspecialchars($event['location']) ?>"
                   data-date="<?= date('Ymd\THis', strtotime($event['event_date'])) ?>">
                    <i class="bi bi-calendar-plus"></i> أضف للتقويم
                </a>
                <button class="btn btn-outline-secondary" id="shareBtn"
                        data-title="<?= htmlspecialchars($event['title']) ?>">
                    <i class="bi bi-share"></i> مشاركة
                </button>
            </div>

            <!-- التسجيل / إلغاء التسجيل بالفعالية -->
            <?php if (!empty($_SESSION['user_id'])): ?>
                <form action="auth.php" method="POST">
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <input type="hidden" name="redirect" value="event.php?id=<?= $event['id'] ?>">
                    <?php if ($is_registered): ?>
                        <input type="hidden" name="action" value="unregister_event">
                        <button class="btn btn-danger w-100"><i class="bi bi-x-circle"></i> إلغاء التسجيل بالفعالية</button>
                    <?php else: ?>
                        <input type="hidden" name="action" value="register_event">
                        <button class="btn btn-success w-100"><i class="bi bi-check-circle"></i> سجّل بالفعالية الآن</button>
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <div class="alert alert-warning mb-0">
                    يجب <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">تسجيل الدخول</button>
                    أولاً للتسجيل بالفعالية.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <h4 class="fw-bold">تفاصيل الفعالية</h4>
        <p class="lh-lg"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
    </div>

    <!-- الفعاليات ذات صلة -->
    <?php if (count($related_events) > 0): ?>
    <section class="mt-5">
        <h4 class="fw-bold mb-3"><i class="bi bi-collection"></i> فعاليات ذات صلة</h4>
        <div class="row g-4">
            <?php foreach ($related_events as $rel): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="assets/img/<?= htmlspecialchars($rel['image']) ?>"
                             onerror="this.src='https://placehold.co/400x220?text=Event'"
                             class="card-img-top" alt="">
                        <div class="card-body">
                            <h6 class="fw-bold"><?= htmlspecialchars($rel['title']) ?></h6>
                            <p class="small text-muted mb-2"><?= date('Y/m/d', strtotime($rel['event_date'])) ?></p>
                            <a href="event.php?id=<?= $rel['id'] ?>" class="btn btn-sm btn-outline-primary">التفاصيل</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
