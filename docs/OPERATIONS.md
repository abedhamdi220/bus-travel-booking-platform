# دليل التشغيل والنشر

## 1. الإعداد المحلي

### المتطلبات الأساسية

ثبّت PHP 8.2 أو أحدث، Composer 2.x، Node.js LTS مع npm، وخادماً لقاعدة البيانات أو SQLite. تأكد من تمكين امتدادات PHP المطلوبة من Laravel، ومنها `mbstring` و`xml` و`curl` و`zip` وامتداد قاعدة البيانات المختار.

### التثبيت

```bash
git clone <repository-url>
cd <repository-directory>

composer install
cp .env.example .env
php artisan key:generate
```

اضبط متغيرات قاعدة البيانات في `.env`، ثم أنشئ المخطط والبيانات الأولية إن توفرت:

```bash
php artisan migrate --seed
```

ثبّت أصول الواجهة وابنها:

```bash
npm install
npm run build
```

شغّل التطبيق محلياً:

```bash
php artisan serve
```

يشغل `composer run dev` خادم Laravel والطابور وVite بشكل متزامن، وهو مناسب للتطوير اليومي.

## 2. متغيرات البيئة

لا تضع قيمة حقيقية لأي متغير سري في المستودع. استخدم `.env.example` كقالب فقط، وأدر القيم في الخادم من خدمة أسرار أو متغيرات نشر مؤمنة.

| المجموعة | أمثلة على المتغيرات | ملاحظات |
|---|---|---|
| التطبيق | `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_URL` | استخدم `APP_DEBUG=false` في الإنتاج. |
| قاعدة البيانات | `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | خصص حساباً محدود الصلاحيات للتطبيق. |
| الجلسات والطابور | `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` | استخدم Redis أو قاعدة البيانات/خدمة طابور مناسبة في الإنتاج. |
| البريد | `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` | اختبر البريد في بيئة staging قبل الإنتاج. |
| الملفات | `FILESYSTEM_DISK` | نفذ `php artisan storage:link` عندما يستخدم التطبيق القرص العام. |
| الذكاء الاصطناعي والخدمات | المتغيرات المعرفة في `config/ai.php` و`config/services.php` | أضف المفاتيح عبر أسرار النشر فقط. |

## 3. قاعدة البيانات والهجرات

نفذ الهجرات الجديدة بتسلسل زمني ولا تعدّل هجرة طبقت على بيئة قائمة. للإصدار الجديد:

```bash
php artisan migrate --force
```

لا تستخدم الأمر التالي في بيانات إنتاجية لأنه يحذف الجداول:

```bash
php artisan migrate:fresh
```

استخدمه فقط في التطوير أو الاختبار:

```bash
php artisan migrate:fresh --seed
```

قبل نشر تغيير مخطط، خذ نسخة احتياطية مجرّبة، وراجع زمن القفل المتوقع للفهارس الكبيرة، واختبر الترقية على نسخة من البيانات.

## 4. الطابور والإشعارات

إذا كانت الإشعارات أو الرسائل البريدية توضع في الطابور، شغّل عاملاً دائماً تحت systemd أو Supervisor. مثال أمر العامل:

```bash
php artisan queue:work --tries=3 --timeout=90
```

أعد تشغيل العامل بعد نشر شيفرة جديدة:

```bash
php artisan queue:restart
```

راقب أخطاء `failed_jobs` وسجل التطبيق بانتظام. لا تشغّل `queue:listen` في الإنتاج ما لم تكن هناك ضرورة خاصة، لأن `queue:work` أكثر كفاءة.

## 5. بناء الأصول

تعتمد الواجهة على Vite. شغّل البناء في كل إصدار إنتاجي:

```bash
npm ci
npm run build
```

ينتج Vite ملف `public/build/manifest.json`، وهو مطلوب لعرض الواجهات التي تستخدم `@vite`. لا ترفع `node_modules` إلى المستودع أو إلى خادم الإنتاج؛ أعد بناء الأصول من `package-lock.json`.

## 6. قائمة فحص ما قبل النشر

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan queue:restart
```

نفّذ أوامر cache بعد ضبط متغيرات البيئة النهائية. إذا كانت المسارات تتضمن Closures فلا يمكن استخدام `route:cache` قبل تحويل تلك المسارات إلى متحكمات قابلة للتخزين المؤقت.

## 7. التحقق والاختبارات

نفذ قبل الدمج والنشر:

```bash
php artisan optimize:clear
php artisan test
./vendor/bin/pint --test
npm run build
```

عند إضافة مسار API، أضف اختبار Feature يغطي المصادقة والتفويض وحالة التحقق وحالة النجاح. وعند تعديل هجرة، اختبر `migrate:fresh` في قاعدة بيانات معزولة.

## 8. إدارة التخزين والملفات

لا تحفظ ملفات المستخدمين المرفوعة داخل Git. استخدم القرص العام أو تخزيناً كائناً (S3 متوافق) وفق `config/filesystems.php`. أنشئ الرابط الرمزي محلياً أو في الخادم عندما يلزم:

```bash
php artisan storage:link
```

راجع حجم الملفات وأنواعها وقواعد التحقق قبل قبول أي رفع. احتفظ بنسخ احتياطية منفصلة للملفات وللقاعدة.

## 9. الاستجابة للحوادث

عند ظهور خطأ إنتاجي، سجّل وقت الحادث ومعرف الطلب والمستخدم المتأثر دون نسخ كلمات مرور أو رموز أو بيانات دفع إلى التذاكر. ابدأ بـ `storage/logs` ومنصة المراقبة، ثم أعد إنتاج المشكلة في staging. إذا كان التغيير متعلقاً بالقاعدة، أوقف النشر وقيّم مسار rollback قبل تنفيذ أي حذف أو تغيير بيانات.
