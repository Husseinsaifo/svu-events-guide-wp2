document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       1) نموذج التواصل contact.php - تحقق كامل عبر JavaScript
    ========================================================= */
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const nameInput    = document.getElementById('contactName');
            const emailInput   = document.getElementById('contactEmail');
            const messageInput = document.getElementById('contactMessage');
            const alertBox     = document.getElementById('contactAlert');

            let isValid = true;

            // التحقق من الاسم
            if (nameInput.value.trim().length < 3) {
                nameInput.classList.add('is-invalid');
                isValid = false;
            } else {
                nameInput.classList.remove('is-invalid');
            }

            // التحقق من البريد الإلكتروني بصيغة صحيحة
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value.trim())) {
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
            }

            // التحقق من الرسالة
            if (messageInput.value.trim().length < 10) {
                messageInput.classList.add('is-invalid');
                isValid = false;
            } else {
                messageInput.classList.remove('is-invalid');
            }

            alertBox.classList.remove('d-none', 'alert-success', 'alert-danger');

            if (!isValid) {
                alertBox.classList.add('alert-danger');
                alertBox.textContent = 'الرجاء تصحيح الأخطاء الموضحة في الحقول قبل الإرسال.';
                return;
            }

            // نجاح التحقق: إظهار رسالة نجاح Bootstrap Alert وتفريغ النموذج
            alertBox.classList.add('alert-success');
            alertBox.textContent = 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً. شكراً لتواصلك معنا!';
            contactForm.reset();
            contactForm.classList.remove('was-validated');
        });
    }

    /* =========================================================
       2) تقييم النجوم في about.php
    ========================================================= */
    const starRating = document.getElementById('starRating');
    if (starRating) {
        const stars = starRating.querySelectorAll('i');
        let selectedValue = 0;

        stars.forEach(star => {
            star.addEventListener('click', function () {
                selectedValue = parseInt(this.dataset.value);
                highlightStars(selectedValue);
            });
            star.addEventListener('mouseover', function () {
                highlightStars(parseInt(this.dataset.value));
            });
        });

        starRating.addEventListener('mouseleave', function () {
            highlightStars(selectedValue);
        });

        function highlightStars(value) {
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                star.classList.toggle('bi-star-fill', starValue <= value);
                star.classList.toggle('bi-star', starValue > value);
                star.classList.toggle('active', starValue <= value);
            });
        }

        const submitRatingBtn = document.getElementById('submitRatingBtn');
        submitRatingBtn.addEventListener('click', function () {
            if (selectedValue === 0) {
                alert('الرجاء اختيار تقييم بالنجوم أولاً');
                return;
            }
            alert('شكراً لك! تم إرسال تقييمك (' + selectedValue + ' نجوم) بنجاح.');
            document.getElementById('ratingForm').reset();
            highlightStars(0);
            selectedValue = 0;
        });
    }

    /* =========================================================
       3) زر "أضف للتقويم" في event.php (يولّد رابط تقويم Google)
    ========================================================= */
    const addToCalendarBtn = document.getElementById('addToCalendarBtn');
    if (addToCalendarBtn) {
        addToCalendarBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const title    = encodeURIComponent(this.dataset.title);
            const desc     = encodeURIComponent(this.dataset.desc);
            const location = encodeURIComponent(this.dataset.location);
            const date     = this.dataset.date; // بصيغة YYYYMMDDTHHmmss

            const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${date}/${date}&details=${desc}&location=${location}`;
            window.open(url, '_blank');
        });
    }

    /* =========================================================
       4) زر "مشاركة" في event.php
    ========================================================= */
    const shareBtn = document.getElementById('shareBtn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function () {
            const title = this.dataset.title;
            if (navigator.share) {
                navigator.share({ title: title, url: window.location.href })
                    .catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('تم نسخ رابط الفعالية إلى الحافظة، يمكنك الآن مشاركته.');
                });
            }
        });
    }

    /* =========================================================
       5) زر العودة لأعلى الصفحة (Scroll To Top)
    ========================================================= */
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    if (scrollTopBtn) {
        window.addEventListener('scroll', function () {
            scrollTopBtn.style.display = window.scrollY > 300 ? 'flex' : 'none';
        });
        scrollTopBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* =========================================================
       6) الوضع الليلي (Dark Mode) - يُحفظ الاختيار أثناء الجلسة
    ========================================================= */
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
        });
    }

});
