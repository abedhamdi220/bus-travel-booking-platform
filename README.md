# منصة حجز رحلات الحافلات

منصة Laravel لإدارة حجز رحلات الحافلات، تجمع بين واجهة المسافر وعمليات الشركات ولوحة الإدارة، مع واجهات API لعملاء الويب أو الهاتف. تغطي الشفرة إدارة المستخدمين والشركات والسائقين والمركبات والرحلات والمقاعد والحجوزات والمدفوعات والتقييمات والإشعارات.

> تُحفظ أسرار التشغيل خارج المستودع. انسخ `.env.example` إلى `.env` واضبطه محلياً أو عبر متغيرات البيئة في الخادم قبل التشغيل.

## المكونات الرئيسية

| النطاق | المسؤوليات | الطبقات المعنية |
|---|---|---|
| المسافر | إنشاء الحساب، الدخول، البحث، الحجز، الدفع، الملف الشخصي والتقييم | `UserController` و`BookingController` و`PaymentController` وواجهات `resources/views` |
| الشركة | إدارة الملف والرحلات والسائقين والمركبات والحجوزات والإعلانات والإشعارات | `CompanyController` و`DriverController` و`VehicleController` و`AdsLogController` |
| الإدارة | إدارة المستخدمين والشركات والحالات والإشعارات | `AdminController` و`AdminMiddleware` |
| API | عمليات تطبيق الهاتف أو العملاء الخارجيين عبر Sanctum | `routes/api.php` وموثق في [docs/API.md](docs/API.md) |

## المتطلبات

| المتطلب | الإصدار أو الملاحظة |
|---|---|
| PHP | `^8.2` |
| Composer | 2.x |
| Node.js وnpm | إصدار LTS حديث |
| قاعدة البيانات | MySQL أو MariaDB أو SQLite للتطوير المحلي |
| امتدادات PHP | الامتدادات المطلوبة من Laravel وSQLite/MySQL بحسب بيئة التشغيل |

تحدد النسخة الدقيقة للحزم في `composer.lock` و`package-lock.json`. لا تعدّل هذه الملفات يدوياً؛ استخدم Composer وnpm لإدارة التبعيات.

## بدء التشغيل السريع

```bash
git clone <repository-url>
cd <repository-directory>

composer install
cp .env.example .env
php artisan key:generate

# عدّل DB_* وMAIL_* وغيرها في .env، ثم:
php artisan migrate --seed

npm install
npm run build
php artisan serve
```

للتطوير المتزامن، يمكن تشغيل الأوامر المعرفة في Composer:

```bash
composer run dev
```

وللتشغيل الأولي في بيئة محلية مجهزة مسبقاً:

```bash
composer run setup
```

راجع دليل الإعداد التفصيلي وخيارات الإنتاج في [docs/OPERATIONS.md](docs/OPERATIONS.md).

## التوثيق

| المستند | الوصف |
|---|---|
| [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) | بنية التطبيق، الأدوار، وتدفق الطلبات. |
| [docs/API.md](docs/API.md) | مرجع نقاط API والمصادقة وحالات الاستجابة. |
| [docs/OPERATIONS.md](docs/OPERATIONS.md) | التشغيل المحلي، المتغيرات، الهجرات، الاختبارات، والنشر. |
| [.env.example](.env.example) | قالب متغيرات البيئة، بلا أسرار حية. |

## الجودة والاختبارات

نفّذ الفحوصات التالية قبل فتح طلب دمج أو نشر نسخة:

```bash
php artisan optimize:clear
php artisan test
npm run build
```

يمكن أيضاً تشغيل Laravel Pint، المتوفر ضمن تبعيات التطوير، لتنسيق الشفرة:

```bash
./vendor/bin/pint
```

## معايير المساهمة

يجب أن يمر أي تغيير جديد عبر التحقق من المدخلات في `FormRequest` أو داخل المتحكم عند بساطة الحالة، وأن يلتزم بصلاحيات الدور وملكية المورد. لا تُضم ملفات `.env` أو `vendor/` أو `node_modules/` أو ملفات التخزين التشغيلية إلى Git. أضف أو حدّث الاختبارات والتوثيق عند إنشاء مسار أو تغيير عقد API.

## ملاحظات توافق

بعض نقاط API الحالية تحمل أسماء تاريخية، مثل `daleteCompany` و`ubdateDocDriver` و`bus-detailes`. حُفظت هذه الأسماء كما هي لتجنب كسر عملاء API الحاليين. ينبغي تقديم أسماء بديلة محسنة في إصدار متوافق لاحق ثم إعلان خطة إيقاف للأسماء القديمة.
