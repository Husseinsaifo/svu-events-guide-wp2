-- ==========================================================
-- قاعدة بيانات مشروع "فعاليات الجامعة الافتراضية"
-- اسم القاعدة: city_events
-- ==========================================================

CREATE DATABASE IF NOT EXISTS city_events CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE city_events;

-- ----------------------------------------------------------
-- جدول المستخدمين (كما هو مطلوب + عمود نوع المستخدم و is_admin)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,           -- مشفرة عبر password_hash()
    user_type ENUM('طالب','موظف','عضو هيئة تدريس') NOT NULL DEFAULT 'طالب',
    is_admin TINYINT(1) NOT NULL DEFAULT 0,   -- 1 = مشرف يدخل للوحة التحكم
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------
-- جدول الفعاليات (كما هو مطلوب تماماً)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(60) NOT NULL,
    location VARCHAR(150) NOT NULL,
    event_date DATETIME NOT NULL,
    image VARCHAR(255) DEFAULT 'default.jpg',
    is_active TINYINT(1) NOT NULL DEFAULT 1,  -- تستخدم لتحديد "فعالية نشطة" بصفحة index
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------
-- جدول التسجيل بالفعاليات (جدول إضافي ضروري لتنفيذ خاصية
-- "تسجيل / إلغاء تسجيل" و"سجل الفعاليات" المطلوبة في الوصف)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_registration (user_id, event_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- ----------------------------------------------------------
-- بيانات تجريبية
-- ----------------------------------------------------------

-- مستخدم مشرف (admin / admin123)
INSERT INTO users (username, password, user_type, is_admin) VALUES
('admin', '$2y$10$OYwMAh5Be9ZnjOTng25dV.ddi/opmab0ydFf7pg9sZexn/7hYBQB6', 'موظف', 1);
-- ملاحظة: كلمة المرور المشفرة أعلاه تقابل "admin123"

-- مستخدم عادي (student1 / student123)
INSERT INTO users (username, password, user_type, is_admin) VALUES
('student1', '$2y$10$tuakJb2YWpyD5vtqJn1cruh.PBj.KuaImN6YHxRzbUUmLYAy63nza', 'طالب', 0);
-- ملاحظة: كلمة المرور المشفرة أعلاه تقابل "student123"

-- فعاليات تجريبية
INSERT INTO events (title, description, category, location, event_date, image, is_active) VALUES
('يوم مفتوح للجامعة الافتراضية', 'تعرف على برامج الجامعة الافتراضية وخدماتها المختلفة عبر يوم مفتوح يضم جلسات تعريفية وورش عمل.', 'تعريفي', 'القاعة الرئيسية - المبنى A', '2026-08-05 10:00:00', 'event1.jpg', 1),
('ورشة أساسيات الأمن السيبراني', 'ورشة تدريبية عملية تغطي أساسيات الأمن السيبراني وأشهر أنواع الثغرات وطرق الحماية منها.', 'تقني', 'مخبر الحاسوب - المبنى B', '2026-08-12 12:00:00', 'event2.jpg', 1),
('مؤتمر ريادة الأعمال الطلابي', 'مؤتمر سنوي يجمع الطلاب أصحاب المشاريع الريادية لعرض أفكارهم أمام لجنة تحكيم متخصصة.', 'ريادة أعمال', 'المسرح الجامعي', '2026-09-01 09:00:00', 'event3.jpg', 1),
('دورة تطوير الواجهات الأمامية', 'دورة مكثفة حول HTML وCSS وJavaScript وBootstrap لبناء واجهات مستخدم احترافية.', 'تقني', 'قاعة التدريب 2', '2026-09-15 11:00:00', 'event4.jpg', 0);
