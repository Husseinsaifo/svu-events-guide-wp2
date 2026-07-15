<?php
require_once 'admin_auth.php';
require_admin();

$page_title = 'تعديل فعالية - لوحة التحكم';
$errors = [];

$event_id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'الفعالية غير موجودة'];
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $location    = trim($_POST['location'] ?? '');
    $event_date  = $_POST['event_date'] ?? '';
    $is_active   = isset($_POST['is_active']) ? 1 : 0;
    $image_name  = $event['image']; // احتفظ بالصورة القديمة افتراضياً

    if ($title === '')       $errors[] = 'عنوان الفعالية مطلوب';
    if ($description === '') $errors[] = 'وصف الفعالية مطلوب';
    if ($category === '')    $errors[] = 'تصنيف الفعالية مطلوب';
    if ($location === '')    $errors[] = 'مكان الفعالية مطلوب';
    if ($event_date === '')  $errors[] = 'تاريخ الفعالية مطلوب';

    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $image_name = uniqid('event_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/img/' . $image_name);
        } else {
            $errors[] = 'صيغة الصورة غير مدعومة (jpg, jpeg, png, webp فقط)';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, category=?, location=?, event_date=?, image=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $description, $category, $location, $event_date, $image_name, $is_active, $event_id]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'تم تعديل الفعالية بنجاح'];
        header('Location: dashboard.php');
        exit;
    }
    // في حال وجود أخطاء، أعِد تعبئة $event من بيانات النموذج لعرضها للمستخدم
    $event = array_merge($event, compact('title', 'description', 'category', 'location', 'event_date', 'is_active'));
}

include 'includes/admin_header.php';
?>

<h3 class="fw-bold mb-4"><i class="bi bi-pencil-square"></i> تعديل الفعالية</h3>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="bg-white rounded-3 shadow-sm p-4" style="max-width:700px;">
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $event['id'] ?>">

        <div class="mb-3">
            <label class="form-label">عنوان الفعالية</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <textarea name="description" rows="4" class="form-control" required><?= htmlspecialchars($event['description']) ?></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">التصنيف</label>
                <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($event['category']) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">المكان</label>
                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($event['location']) ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ ووقت الفعالية</label>
                <input type="datetime-local" name="event_date" class="form-control"
                       value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">صورة الفعالية (اتركها فارغة للإبقاء على الصورة الحالية)</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <img src="../assets/img/<?= htmlspecialchars($event['image']) ?>"
                     onerror="this.src='https://placehold.co/100x70?text=IMG'"
                     class="mt-2 rounded" width="100">
            </div>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" <?= $event['is_active'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">فعالية نشطة (تظهر بالصفحة الرئيسية)</label>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ التعديلات</button>
        <a href="dashboard.php" class="btn btn-secondary">إلغاء</a>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>
