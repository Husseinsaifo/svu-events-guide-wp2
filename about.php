<?php
require_once 'db.php';
$page_title = 'عن الفعالية - الجامعة الافتراضية';
include 'includes/header.php';
?>

<div class="container">
    <h1 class="fw-bold mb-4"><i class="bi bi-info-circle"></i> عن فعاليات الجامعة الافتراضية</h1>

    <div class="row g-4 align-items-center mb-5">
        <div class="col-md-6">
            <h4 class="fw-bold">هدف الفعاليات</h4>
            <p class="lh-lg">
                تهدف فعاليات الجامعة الافتراضية إلى إثراء التجربة الأكاديمية للطلاب من خلال أنشطة
                تعليمية وتدريبية وتوعوية متنوعة، تجمع بين الطلاب وأعضاء هيئة التدريس والموظفين
                في بيئة تفاعلية تعزز التواصل وتبادل الخبرات.
            </p>

            <h4 class="fw-bold mt-4">الأدوار والمسؤوليات</h4>
            <ul class="lh-lg">
                <li><strong>الطالب:</strong> المشاركة والتسجيل في الفعاليات المناسبة لاهتماماته.</li>
                <li><strong>عضو هيئة التدريس:</strong> الإشراف الأكاديمي وتقديم المحتوى العلمي.</li>
                <li><strong>الموظف / المشرف:</strong> التنظيم الإداري وإدارة بيانات الفعاليات.</li>
            </ul>
        </div>
        <div class="col-md-6">
            <img src="https://placehold.co/600x400?text=About+Events" class="img-fluid rounded-4 shadow" alt="عن الفعاليات">
        </div>
    </div>

    <!-- نموذج تقييم الفعالية -->
    <section class="rating-section p-4 rounded-4 shadow-sm">
        <h4 class="fw-bold mb-3"><i class="bi bi-star-half"></i> قيّم تجربتك مع فعاليات الجامعة</h4>

        <form id="ratingForm" class="row g-3" onsubmit="return false;">
            <div class="col-md-6">
                <label class="form-label">اسمك</label>
                <input type="text" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">التقييم</label>
                <div class="star-rating" id="starRating">
                    <i class="bi bi-star" data-value="1"></i>
                    <i class="bi bi-star" data-value="2"></i>
                    <i class="bi bi-star" data-value="3"></i>
                    <i class="bi bi-star" data-value="4"></i>
                    <i class="bi bi-star" data-value="5"></i>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">ملاحظاتك (اختياري)</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" id="submitRatingBtn">إرسال التقييم</button>
            </div>
        </form>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
