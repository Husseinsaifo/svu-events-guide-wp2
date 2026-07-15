<?php
require_once 'db.php';
$page_title = 'اتصل بنا - الجامعة الافتراضية';
include 'includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <h1 class="fw-bold mb-4 text-center"><i class="bi bi-envelope"></i> اتصل بنا</h1>

            <!-- منطقة عرض رسالة النجاح/الخطأ -->
            <div id="contactAlert" class="alert d-none" role="alert"></div>

            <form id="contactForm" novalidate>
                <div class="mb-3">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" class="form-control" id="contactName" required minlength="3">
                    <div class="invalid-feedback">الرجاء إدخال اسم لا يقل عن 3 أحرف.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" id="contactEmail" required>
                    <div class="invalid-feedback">الرجاء إدخال بريد إلكتروني صحيح.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">الرسالة</label>
                    <textarea class="form-control" id="contactMessage" rows="5" required minlength="10"></textarea>
                    <div class="invalid-feedback">الرجاء كتابة رسالة لا تقل عن 10 أحرف.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-send"></i> إرسال الرسالة
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
