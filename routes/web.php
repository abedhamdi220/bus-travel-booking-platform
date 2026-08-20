<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdsLogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

// ========================================================
// 🌍 المسارات العامة (الزوار)
// ========================================================
Route::get('/', [FrontController::class, 'index'])->name('landing');
Route::get('/home', [FrontController::class, 'index'])->name('home');
Route::get('/search-trips', [FrontController::class, 'search'])->name('search.trips');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');
Route::post('/resetPassword', [UserController::class, 'resetPassword'])->name('resetPassword');
Route::get('/showForgotPasswordForm', function () {
    return view('auth.forgot-password');
})->withoutMiddleware([ThrottleRequests::class])->name('showForgotPasswordForm');
Route::get('/forgotPassword', [UserController::class, 'forgotPassword'])->name('forgot-password');


// ========================================================
// 👨‍💼 مسارات الركاب / المستخدمين (يجب تسجيل الدخول كراكب)
// ========================================================
Route::middleware(['auth'])->group(function () {
    // لوحة تحكم الراكب والملف الشخصي
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroyAccount'])->name('profile.destroy');

    // عمليات الحجز، الدفع، والتقييم (نُقلت إلى هنا)
    Route::get('/trip/{tripId}/seats', [BookingController::class, 'showSeatSelection'])->name('booking.seats');
    Route::post('/trip/{tripId}/book', [BookingController::class, 'booking'])->name('booking.store');

    Route::get('/payment/{tripId}', [PaymentController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/paysham', [PaymentController::class, 'paysham'])->name('payment.paysham');

    Route::post('/reviews/company', [ReviewController::class, 'ratingCompany'])->name('reviews.company');
});
// ========================================================
// 🛡️ مسارات الإدارة العليا (Super Admin) - محمية بـ AdminMiddleware
// ========================================================

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {

    // لوحة التحكم الأساسية
    Route::get('/dashboard', [AdminController::class, 'infoForWeb'])->name('admin.dashboard');

    // 💡 مسار تحديث بروفايل المشرف المستقل
    Route::put('/profile/update', [AdminController::class, 'updateAdminProfile'])->name('admin.profile.update');

    // --- عمليات إدارة الشركات ---
    Route::put('/company/state/{id}', [AdminController::class, 'updateStateCompany']);
    Route::delete('/company/delete/{id}', [AdminController::class, 'daleteCompany']);

    // --- عمليات إدارة المستخدمين ---
    Route::put('/user/block/{id}', [AdminController::class, 'blockUser']);
    Route::delete('/user/delete/{id}', [AdminController::class, 'deleteUser']);
});
// ========================================================
// 🏢 مسارات الشركة (يجب تسجيل الدخول كشركة)
// ========================================================
Route::middleware(['auth:company'])->group(function () {

    // --- 1. واجهات الشركة الأساسية (Views with Data) ---
    Route::get('/company/dashboard', [CompanyController::class, 'indexDashboard'])->name('company.dashboard');
    Route::get('/company/profile', [CompanyController::class, 'indexProfile'])->name('company.profile');
Route::post('/company/offers/store', [AdsLogController::class, 'storeAds'])->name('company.offers.store');
    Route::delete('/company/offers/delete/{id}', [AdsLogController::class, 'deleteAds'])->name('company.offers.delete');
    // 🔴 تم ربط هذه المسارات بالمتحكمات بدلاً من الـ Views الفارغة
    Route::get('/company/trips', [CompanyController::class, 'showTrips'])->name('company.trips');
    Route::get('/company/bookings', [BookingController::class, 'showCompanyBookings'])->name('company.bookings');
    Route::get('/company/buses', [VehicleController::class, 'getBuses'])->name('company.buses');
    Route::get('/company/myDrivers', [DriverController::class, 'getCompanyDrivers'])->name('company.mydrivers');
    Route::get('/company/offers', [AdsLogController::class, 'index'])->name('company.offers');

    // --- 2. واجهات الإضافة (Forms) ---
    Route::get('/company/addBus', function () { return view('company.add-bus'); })->name('company.add-bus');
    Route::get('/company/addDriver', function () { return view('company.add-driver'); })->name('company.drivers.create');
    Route::get('/company/addTrip', [CompanyController::class, 'createTrip'])->name('company.addTrip');

    // --- 3. العمليات التشغيلية (Actions) ---
    // الرحلات
    Route::post('/company/addTrip', [CompanyController::class, 'storeTrip'])->name('company.trip.store'); // تم التعديل
    Route::delete('/company/deleteTrip/{id}', [CompanyController::class, 'deleteTrip'])->name('company.trip.delete');
    Route::put('/company/updateTrip/{id}', [CompanyController::class, 'updateTrip'])->name('company.trip.update');

    // الحافلات
    Route::post('/company/addBusData', [VehicleController::class, 'addBus'])->name('company.buses.store');
    Route::delete('/company/deleteVehicle/{id}', [VehicleController::class, 'deleteVehicle'])->name('company.buses.delete');
    Route::get('/company/bus-detailes/{id}', [VehicleController::class, 'busDetailes'])->name('company.buses.details');

    // السائقين
    Route::post('/company/addDriver', [DriverController::class, 'registerDriver'])->name('company.drivers.store');
    Route::delete('/company/deleteDriver/{id}', [DriverController::class, 'delete'])->name('company.drivers.delete');

    // الحجوزات
    Route::delete('/company/cancelBooking/{id}', [BookingController::class, 'companyCancelBooking'])->name('company.bookings.cancel');

    // الإعدادات
    Route::put('/company/update-profile', [CompanyController::class, 'updateProfile'])->name('company.profile.update');
    Route::put('/company/update-password', [CompanyController::class, 'updatePassword'])->name('company.password.update');
    Route::post('/company/update-logo', [CompanyController::class, 'updateLogo'])->name('company.logo.update');

    // الإشعارات
    Route::get('/company/notifications/fetch', [CompanyController::class, 'fetchNotifications'])->name('company.notifications.fetch');
    Route::post('/company/notifications/mark-as-read', [CompanyController::class, 'markNotificationsAsRead'])->name('company.notifications.markAsRead');
});


// ========================================================
// مسارات الإدارة والسائقين (حسب ملفاتك القديمة)
// ========================================================
Route::get('/driver.pending', function () {
    return view('driver.pending');
})->name('driver.pending');
Route::get('/driver/rejected', function () {
    return view('driver.rejected');
})->name('driver.rejected');
Route::get('company/rejected', function () {
    return view('company.rejected');
})->name('company.rejected');
