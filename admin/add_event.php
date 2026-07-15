<?php
require_once 'admin_auth.php';
require_admin();

$page_title = 'إضافة فعالية - لوحة التحكم';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $location    = trim($_POST['location'] ?? '');
    $event_date  = $_POST['event_date'] ?? '';
    $is_active   = isset($_POST['is_active']) ? 1 : 0;
    $image_name  = 'default.jpg';

    // التحقق من الحقول الأساسية
    if ($title === '')       $errors[] = 'عنوان الفعالية مطلوب';
    if ($description === '') $errors[] = 'وصف الفعالية مطلوب';
    if ($category === '')    $errors[] = 'تصنيف الفعالية مطلوب';
    if ($location === '')    $errors[] = 'مكان الفعالية مطلوب';
    if ($event_date === '')  $errors[] = 'تاريخ الفعالية مطلوب';

    // رفع الصورة إن وُجدت
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $image_name = uniqid('event_') . '.' . $ext;
            $target = __DIR__ . '/../assets/img/' . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        } else {
            $errors[] = 'صيغة الصورة غير مدعومة (jpg, jpeg, png, webp فقط)';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, category, location, event_date, image, is_active)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $category, $location, $event_date, $image_name, $is_active]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'تمت إضافة الفعالية بنجاح'];
        header('Location: dashboard.php');
        exit;
    }
}

include 'includes/admin_header.php';
?>

<h3 class="fw-bold mb-4"><i class="bi bi-plus-circle"></i> إضافة فعالية جديدة</h3>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="bg-white rounded-3 shadow-sm p-4" style="max-width:700px;">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">عنوان الفعالية</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <textarea name="description" rows="4" class="form-control" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">التصنيف</label>
                <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($_POST['category'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">المكان</label>
                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ ووقت الفعالية</label>
                <input type="datetime-local" name="event_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">صورة الفعالية</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            </div>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
            <label class="form-check-label" for="is_active">فعالية نشطة (تظهر بالصفحة الرئيسية)</label>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ الفعالية</button>
        <a href="dashboard.php" class="btn btn-secondary">إلغاء</a>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>
