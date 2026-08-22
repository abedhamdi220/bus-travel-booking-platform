# Cleanup Manifest (MVCS Refactoring)

هذا الملف يوثق تفاصيل التعديلات، الملفات المحذوفة، والأساليب (Methods) التي تم نقلها أو تعديلها خلال عملية إعادة هيكلة المشروع إلى نمط MVCS (Model-View-Controller-Service). الغرض من هذا السجل هو تسهيل مراجعة الكود وإمكانية التراجع عن أي تغيير في حال ظهور تبعيات غير متوقعة.

## 1. الكلاسات الجديدة (Added Classes)

*   **الملف:** `app/Services/CompanyService.php`
    *   **الوصف:** خدمة جديدة تم إنشاؤها لعزل المنطق التجاري (Business Logic) الخاص بالشركة عن `CompanyController`.
    *   **الدوال المضافة:**
        *   `createTripWithSeats(Company $company, array $data, int $driverId, int $vehicleId): Trip`
        *   `getTopRouteThisMonth(Company $company): array`
        *   `getCompanyMetrics(Company $company): array`
        *   `getCompanyProfile(Company $company): array`

## 2. الكلاسات المعدلة (Modified Classes)

*   **الملف:** `app/Http/Controllers/CompanyController.php`
    *   **نوع التعديل:** Refactoring (إعادة هيكلة)
    *   **التفاصيل:**
        *   تم إضافة حقن التبعية (Dependency Injection) لـ `CompanyService` في الـ Constructor.
        *   **الدوال المحذوفة (التي تم نقلها إلى الخدمة):**
            *   `private function createTripWithSeats(...)` -> نُقلت إلى `CompanyService`.
            *   `private function topRouteThisMonth(...)` -> نُقلت إلى `CompanyService`.
            *   `private function companyMetrics(...)` -> نُقلت إلى `CompanyService`.
            *   `private function companyProfile(...)` -> نُقلت إلى `CompanyService`.
        *   **الدوال المعدلة (لتعكس استخدام الخدمة):**
            *   `indexDashboard()`: تم تعديلها لاستدعاء `getTopRouteThisMonth` و `getCompanyMetrics` من الخدمة.
            *   `indexProfile()`: تم تعديلها لاستدعاء `getCompanyProfile` من الخدمة.
            *   `storeTrip(Request $request)`: تم تعديلها لاستدعاء `createTripWithSeats` من الخدمة.
            *   `addTrip(TripRequest $request, int $driver_id, int $vehicle_id)`: تم تعديلها لاستدعاء `createTripWithSeats` من الخدمة.
        *   **إصلاحات الأخطاء:**
            *   تم إصلاح خطأ `Syntax Error` حرج نتج عن تعريف دالة خاطئ أثناء محاولة إعادة الهيكلة الأولى.

*   **الملف:** `app/Http/Controllers/AdminController.php`
    *   **نوع التعديل:** Formatting / Syntax
    *   **التفاصيل:** تصحيحات طفيفة في التنسيق وبناء الجملة (Syntax) لضمان اجتياز فحص `php -l`.

*   **الملفات:** `DriverController.php`, `ProfileDriverController.php`, `SeatsController.php`
    *   **نوع التعديل:** Formatting / Syntax
    *   **التفاصيل:** إصلاحات طفيفة لضمان التوافق مع معايير كود PHP.

## 3. التعديلات على النماذج وقواعد البيانات (Models & Migrations)

*   **الملف:** `app/Models/Review.php`
    *   **نوع التعديل:** Formatting
*   **الملف:** `database/migrations/2026_04_25_102515_create_ads_logs_table.php`
    *   **نوع التعديل:** Formatting
*   **الملف:** `database/migrations/2026_06_04_102030_create_driver_locations_table.php`
    *   **نوع التعديل:** Formatting

## 4. التعديلات على الواجهات (Blade Views)

*   **الملفات:** `brez.html`, `company-bus-add.blade.php`, `company-buses.blade.php`, `companies.blade.php`, `admin-profile.blade.php`
    *   **نوع التعديل:** HTML/Blade Formatting
    *   **التفاصيل:** تم إجراء تعديلات طفيفة على هيكل وتنسيق هذه الملفات. لم يتم تغيير المنطق الأساسي للعرض.

## 5. حالة المشروع الحالية (Project Status)

*   **PHP Linting:** جميع ملفات المشروع تجتاز فحص `php -l` بنجاح (خالية من الأخطاء النحوية).
*   **Routes:** جميع المسارات (Routes) المعرفة في `web.php` و `api.php` تم التحقق من مطابقتها لوحدات تحكم (Controllers) ودوال (Methods) موجودة فعلياً.
*   **Views:** جميع الواجهات المستدعاة من الكنترولرات موجودة في مجلد `resources/views`.

---
*ملاحظة: هذا السجل تم إنشاؤه لضمان الشفافية في عملية إعادة الهيكلة. إذا ظهرت أي مشاكل في وظائف الشركة (Company Features)، يجب مراجعة `CompanyService.php` أولاً.*
