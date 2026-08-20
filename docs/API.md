# مرجع API

## القواعد العامة

يفترض هذا المرجع أن مسارات `routes/api.php` تعمل تحت البادئة الافتراضية `/api`.

```text
https://<host>/api/<endpoint>
```

تُرسل الطلبات بجسم JSON عند استخدام API، مع الترويسة التالية للنقاط المحمية بـ Sanctum:

```http
Accept: application/json
Authorization: Bearer <token>
Content-Type: application/json
```

> لا يوثق هذا المستند حقول JSON الداخلية لكل متحكم ما لم تكن متعاقداً عليها صراحة في `FormRequest`. ينبغي اعتماد استجابات موحدة وإضافة اختبارات feature لكل نقطة نهاية قبل اعتبارها عقداً عاماً ثابتاً.

## المصادقة والحماية

| الوسيط | المعنى |
|---|---|
| `auth:sanctum` | يتطلب رمز Sanctum صالحاً للمستخدم. |
| `ApprovedCompany` | يتطلب جلسة شركة معتمدة بحسب منطق الوسيط الحالي. |
| لا يوجد | نقطة عامة؛ يجب حماية بياناتها ومعدل الطلبات في طبقة البنية التحتية عند اللزوم. |

## الواجهة العامة والحسابات

| الطريقة | المسار | الحماية | المتحكم::الدالة | الغرض |
|---|---|---|---|---|
| `GET` | `/front/home` | عام | `FrontController::index` | بيانات أو واجهة الصفحة الرئيسية. |
| `GET` | `/front/search` | عام | `FrontController::search` | البحث عن الرحلات. |
| `POST` | `/register` | عام | `UserController::registerApi` | تسجيل مستخدم جديد. |
| `POST` | `/login` | عام | `UserController::loginApi` | تسجيل الدخول وإصدار بيانات الجلسة/الرمز. |
| `DELETE` | `/logout` | Sanctum | `UserController::logout` | إنهاء جلسة أو رمز المستخدم. |
| `GET` | `/user` | Sanctum | `UserController::getUser` | استرجاع الحساب الحالي. |

### مثال تسجيل الدخول

```http
POST /api/login
Accept: application/json
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "secret"
}
```

راجع قواعد `UserController` الفعلية قبل نشر عميل جديد، لأن بعض حقول التسجيل تعتمد على الدور ونوع الحساب.

## الملف الشخصي

| الطريقة | المسار | الحماية | الغرض |
|---|---|---|---|
| `PUT` | `/profile/update/{id}` | Sanctum | تحديث ملف المستخدم. |
| `DELETE` | `/profile/destroyAccount` | Sanctum | حذف حساب المستخدم الحالي. |
| `PUT` | `/profile/company/update/{id}` | Sanctum | تحديث ملف الشركة. |
| `PUT` | `/driver/ubdateDocDriver` | Sanctum | تحديث وثائق السائق. |

> يوجد مسار تاريخي إضافي بصيغة `/profile//{id}`. لا تستخدمه في عملاء جدد؛ استخدم `/profile/update/{id}`.

## الشركات والرحلات

| الطريقة | المسار | الحماية | الغرض |
|---|---|---|---|
| `POST` | `/company/register` | عام | تسجيل شركة. |
| `POST` | `/company/addTrip/{driver_id}/{vehicle_id}` | شركة معتمدة | إنشاء رحلة وربطها بسائق ومركبة. |
| `GET` | `/company/showMyTrip` | شركة معتمدة | استعراض رحلات الشركة. |
| `PUT` | `/company/update/{id}` | شركة معتمدة | تحديث رحلة. |
| `DELETE` | `/company/deleteTrip/{id}` | شركة معتمدة | حذف رحلة. |
| `GET` | `/company/showMenuPassengerOnTrip/{tripId}` | Sanctum + شركة معتمدة | عرض معلومات رحلة/ركاب وفق تنفيذ المتحكم. |
| `GET` | `/company/searchInCompanies/{name}` | Sanctum | البحث عن الشركات بالاسم. |

### مدخلات رحلة الشركة

ينبغي إرسال `driver_id` و`vehicle_id` في المسار، وتحديد بيانات الرحلة وفق التحقق في `TripRequest` عند استخدامه. تشمل الحقول التشغيلية المتوقعة عادةً:

```json
{
  "cost": 25000,
  "dateTrip": "2026-12-01",
  "timeTrip": "09:00",
  "departure_city": "Damascus",
  "destination": "Aleppo",
  "trip_type": "one_way",
  "recurrence": "one_time"
}
```

قد تتطلب الرحلات الأسبوعية حقل `days`، وقد تتطلب الرحلة ذهاباً وإياباً حقل `break_duration` بحسب قواعد الطلب المعتمدة في الإصدار المنشور.

## الحجوزات والدفع

| الطريقة | المسار | الحماية | الغرض |
|---|---|---|---|
| `POST` | `/booking/booking/{tripId}` | Sanctum | إنشاء حجز لرحلة. |
| `DELETE` | `/booking/cancelBooking/{bookingId}` | Sanctum | إلغاء حجز. |
| `POST` | `/payment/paysham` | Sanctum | بدء عملية دفع. |
| `POST` | `/payment/callback` | عام* | استقبال رد مزود الدفع. |

> يجب حماية `payment/callback` بتوقيع أو سر مشترك أو آلية موثقة من مزود الدفع. لا تعتمد على كونه مساراً عاماً دون تحقق من المصدر.

## التقييمات والبحث

| الطريقة | المسار | الحماية | الغرض |
|---|---|---|---|
| `POST` | `/Review/ratingCompany` | Sanctum | إنشاء أو تحديث تقييم شركة. |
| `POST` | `/Review/ratingDriver/{id}` | Sanctum | إنشاء أو تحديث تقييم سائق. |
| `GET` | `/Review/getAvgDriver/{id}` | عام | متوسط تقييم سائق. |
| `GET` | `/Review/getAvgCompany/{id}` | عام | متوسط تقييم شركة. |
| `GET` | `/Review/countReviewsCompany/{id}` | عام | عدد تقييمات شركة. |
| `GET` | `/Review/countReviewsDriver/{id}` | عام | عدد تقييمات سائق. |
| `GET` | `/trip/search/{company_id}` | Sanctum | البحث في رحلات شركة. |

## السائقون والمركبات

| الطريقة | المسار | الحماية | الغرض |
|---|---|---|---|
| `PUT` | `/driver/acceptDriver/{id}` | شركة معتمدة | اعتماد سائق. |
| `POST` | `/driver/registerDriver/{id}` | Sanctum | تسجيل أو ربط سائق بحسب المتحكم. |
| `GET` | `/driver/showAllDriver` | عام | استعراض السائقين. |
| `POST` | `/driver/stateDriver/{driver_id}` | عام | تغيير حالة سائق؛ يوصى بحمايته قبل الإنتاج. |
| `GET` | `/driver/showDriver/{id}` | عام | استعراض سائق. |
| `POST` | `/vehicle/addBus` | Sanctum | إضافة مركبة. |
| `GET` | `/vehicle/countVehicle` | شركة معتمدة | إحصاء المركبات. |
| `PUT` | `/vehicle/stateVehicle/{id}` | شركة معتمدة | تغيير حالة مركبة. |
| `PUT` | `/vehicle/updateVehicle/{id}` | شركة معتمدة | تحديث مركبة. |
| `DELETE` | `/vehicle/deleteVehicle/{id}` | شركة معتمدة | حذف مركبة. |
| `POST` | `/driverLocation/store` | Sanctum | تخزين موقع سائق. |
| `GET` | `/driverLocation/show` | Sanctum | استرجاع مواقع السائق. |

## الإدارة والإعلانات والإشعارات

| الطريقة | المسار | الحماية الحالية | الغرض |
|---|---|---|---|
| `PUT` | `/admin/updateInfoUser/{user_id}` | غير معلن | تحديث مستخدم. |
| `PUT` | `/admin/updateInfoCompany/{company_id}` | غير معلن | تحديث شركة. |
| `PUT` | `/admin/updateStateCompany/{company_id}` | غير معلن | تغيير حالة شركة. |
| `DELETE` | `/admin/daleteCompany/{company_id}` | غير معلن | حذف شركة. |
| `DELETE` | `/admin/deleteUser/{user_id}` | غير معلن | حذف مستخدم. |
| `GET` | `/admin/getAdminUnNotification` | Sanctum | إشعارات المشرف غير المقروءة. |
| `GET` | `/admin/getAdminAllNotification` | Sanctum | جميع إشعارات المشرف. |
| `POST` | `/adslog/storeAds` | شركة معتمدة | إنشاء إعلان. |
| `PUT` | `/adslog/updateAds/{id}` | شركة معتمدة | تعديل إعلان. |
| `DELETE` | `/adslog/deleteAds/{id}` | شركة معتمدة | حذف إعلان. |
| `GET` | `/adslog/showAdsLog/{id}` | Sanctum | عرض إعلان. |

## رموز الاستجابة

| الرمز | الاستخدام المتوقع |
|---:|---|
| `200` | قراءة أو تعديل ناجح. |
| `201` | إنشاء مورد جديد بنجاح. |
| `204` | حذف ناجح دون محتوى. |
| `401` | غياب أو عدم صحة المصادقة. |
| `403` | مستخدم مصادق لكن غير مخول أو شركة غير معتمدة. |
| `422` | بيانات لا تطابق قواعد التحقق. |
| `500` | خطأ غير معالج؛ يجب تسجيله ومراجعته. |

## توصيات أمنية قبل الإتاحة العامة

| الأولوية | الإجراء |
|---|---|
| حرجة | أضف وسيط إدارة صريحاً لجميع مسارات `/admin/*` في API. |
| حرجة | وثّق وحقق توقيع رد مزود الدفع. |
| عالية | أضف rate limiting لمسارات تسجيل الدخول والتسجيل والبحث. |
| عالية | وحّد تسمية المسارات وأضف aliases متوافقة قبل إيقاف الأسماء التاريخية. |
| متوسطة | أضف اختبارات Feature لكل نقطة API وتوثيق OpenAPI مولد آلياً عند استقرار العقود. |
