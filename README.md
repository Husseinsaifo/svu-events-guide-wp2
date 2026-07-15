# مشروع فعاليات الجامعة الافتراضية

## بيئة العمل المطلوبة
- خادم محلي يدعم PHP + MySQL: **XAMPP** أو **Laragon** (الأسهل على Windows).
- محرر أكواد: **VS Code**.
- متصفح لعرض النتيجة.

## خطوات التشغيل

1. انسخ مجلد `project` كاملاً إلى:
   - XAMPP: `C:\xampp\htdocs\project`
   - Laragon: `C:\laragon\www\project`

2. شغّل Apache و MySQL من لوحة تحكم XAMPP/Laragon.

3. افتح phpMyAdmin (`http://localhost/phpmyadmin`)، ثم استورد ملف `database.sql`
   (سينشئ قاعدة البيانات `city_events` والجداول والبيانات التجريبية تلقائياً).

4. تأكد من إعدادات الاتصال في `db.php` (القيم الافتراضية تعمل مباشرة مع XAMPP):
   ```
   $db_user = 'root';
   $db_pass = '';
   ```

5. افتح المتصفح على:
   - الموقع للمستخدم: `http://localhost/project/index.php`
   - لوحة تحكم الأدمن: `http://localhost/project/admin/login.php`

## حسابات تجريبية
| النوع | اسم المستخدم | كلمة المرور |
|---|---|---|
| مشرف (Admin) | admin | admin123 |
| طالب | student1 | student123 |

## ملاحظة هامة
تم إضافة جدول `registrations` (غير مذكور صراحة في وصف قاعدة البيانات الأصلي)
لأنه ضروري تقنياً لتنفيذ ميزة "التسجيل/إلغاء التسجيل بالفعالية" و"سجل الفعاليات"
المطلوبة في وصف صفحة index.php. تم إبقاؤه بأبسط شكل ممكن (3 أعمدة فقط).
