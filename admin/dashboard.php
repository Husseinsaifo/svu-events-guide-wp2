<?php
require_once 'admin_auth.php';
require_admin();

$page_title = 'إدارة الفعاليات - لوحة التحكم';

$events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();

include 'includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><i class="bi bi-list-ul"></i> إدارة الفعاليات</h3>
    <a href="add_event.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> إضافة فعالية جديدة</a>
</div>

<div class="table-responsive bg-white rounded-3 shadow-sm p-2">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>العنوان</th>
                <th>التصنيف</th>
                <th>المكان</th>
                <th>التاريخ</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($events) === 0): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">لا توجد فعاليات مضافة بعد.</td></tr>
        <?php endif; ?>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?= $event['id'] ?></td>
                <td>
                    <img src="../assets/img/<?= htmlspecialchars($event['image']) ?>"
                         onerror="this.src='https://placehold.co/60x45?text=IMG'"
                         width="60" height="45" style="object-fit:cover;" class="rounded">
                </td>
                <td><?= htmlspecialchars($event['title']) ?></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($event['category']) ?></span></td>
                <td><?= htmlspecialchars($event['location']) ?></td>
                <td><?= date('Y/m/d', strtotime($event['event_date'])) ?></td>
                <td>
                    <?php if ($event['is_active']): ?>
                        <span class="badge bg-success">نشطة</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">غير نشطة</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i> تعديل
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteModal<?= $event['id'] ?>">
                        <i class="bi bi-trash"></i> حذف
                    </button>
                </td>
            </tr>

            <!-- Modal تأكيد الحذف -->
            <div class="modal fade" id="deleteModal<?= $event['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">تأكيد الحذف</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            هل أنت متأكد من حذف الفعالية
                            "<strong><?= htmlspecialchars($event['title']) ?></strong>"؟
                            لا يمكن التراجع عن هذا الإجراء.
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn btn-danger">تأكيد الحذف</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/admin_footer.php'; ?>
