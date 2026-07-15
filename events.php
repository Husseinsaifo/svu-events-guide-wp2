<?php
require_once 'db.php';
$page_title = 'الفعاليات - الجامعة الافتراضية';

// دعم فلترة بسيطة حسب التصنيف (لمسة إضافية اختيارية)
$category_filter = $_GET['category'] ?? '';

if ($category_filter !== '') {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE category = ? ORDER BY event_date ASC");
    $stmt->execute([$category_filter]);
} else {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
}
$events = $stmt->fetchAll();

// جلب قائمة التصنيفات لعرضها في الفلتر
$categories = $pdo->query("SELECT DISTINCT category FROM events")->fetchAll(PDO::FETCH_COLUMN);

include 'includes/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="fw-bold mb-0"><i class="bi bi-calendar3"></i> جميع الفعاليات</h1>

        <form class="d-flex gap-2" method="GET">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">كل التصنيفات</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $category_filter === $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (count($events) === 0): ?>
        <div class="alert alert-info">لا توجد فعاليات مطابقة حالياً.</div>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($events as $event): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card event-card h-100 shadow-sm">
                    <img src="assets/img/<?= htmlspecialchars($event['image']) ?>"
                         onerror="this.src='https://placehold.co/400x220?text=Event'"
                         class="card-img-top" alt="<?= htmlspecialchars($event['title']) ?>">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary align-self-start mb-2"><?= htmlspecialchars($event['category']) ?></span>
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($event['title']) ?></h5>
                        <p class="card-text text-muted small">
                            <i class="bi bi-calendar-event"></i> <?= date('Y/m/d', strtotime($event['event_date'])) ?>
                            &nbsp;|&nbsp;
                            <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($event['location']) ?>
                        </p>
                        <p class="card-text flex-grow-1">
                            <?= htmlspecialchars(mb_substr($event['description'], 0, 90)) ?>...
                        </p>
                        <a href="event.php?id=<?= $event['id'] ?>" class="btn btn-primary mt-auto">عرض التفاصيل</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
