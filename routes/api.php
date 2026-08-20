<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdsLogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverLocationsController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileCompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileDriverController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('front/home', [FrontController::class, 'index']);
 Route::get('front/search', [FrontController::class, 'search']);
Route::get('/user', [UserController::class, 'getUser'])->middleware('auth:sanctum');
Route::post('payment/paysham', [PaymentController::class, 'paysham'])->middleware('auth:sanctum');
Route::post('/register', [UserController::class, 'registerApi']);
Route::post('payment/callback', [PaymentController::class, 'callback']);
Route::post('/login', [UserController::class, 'loginApi']);
Route::delete('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
/////////////profile user
Route::delete('profile/destroyAccount', [ProfileController::class, 'destroyAccount'])->middleware('auth:sanctum');
Route::put('profile/update/{id}', [ProfileController::class, 'update'])->middleware('auth:sanctum');
Route::put('profile//{id}', [ProfileController::class, 'update'])->middleware('auth:sanctum');
///////////profile company
Route::put('profile/company/update/{id}', [ProfileCompanyController::class, 'update'])->middleware('auth:sanctum');

Route::post('company/register', [CompanyController::class, 'store']);
Route::post('company/addTrip/{driver_id}/{vehicle_id}', [CompanyController::class, 'addTrip'])->middleware('ApprovedCompany');
Route::get('company/showMyTrip', [CompanyController::class, 'showMyTrip'])->middleware('ApprovedCompany');
Route::put('company/update/{id}', [CompanyController::class, 'updateTrip'])->middleware('ApprovedCompany');
Route::delete('company/deleteTrip/{id}', [CompanyController::class, 'deleteTrip'])->middleware('ApprovedCompany');

Route::post('booking/booking/{tripId}', [BookingController::class, 'booking'])->middleware('auth:sanctum');
Route::delete('booking/cancelBooking/{bookingId}', [BookingController::class, 'cancelBooking'])->middleware('auth:sanctum');
Route::get('company/showMenuPassengerOnTrip/{tripId}', [CompanyController::class, 'showMenuPassengerOnTrip'])->middleware('auth:sanctum')->middleware('ApprovedCompany');

Route::post('Review/ratingCompany', [ReviewController::class, 'ratingCompany'])->middleware('auth:sanctum');
Route::post('Review/ratingDriver/{id}', [ReviewController::class, 'ratingDriver'])->middleware('auth:sanctum');
Route::get('Review/getAvgDriver/{id}', [ReviewController::class, 'getAvgDriver']);
Route::get('Review/getAvgCompany/{id}', [ReviewController::class, 'getAvgCompany']);
Route::get('Review/countReviewsCompany/{id}', [ReviewController::class, 'countReviewsCompany']);
Route::get('Review/countReviewsDriver/{id}', [ReviewController::class, 'countReviewsDriver']);
Route::get('trip/search/{company_id}', [TripController::class, 'search'])->middleware('auth:sanctum');
Route::get('company/searchInCompanies/{name}', [CompanyController::class, 'searchInCompanies'])->middleware('auth:sanctum');

Route::put('admin/updateInfoUser/{user_id}', [AdminController::class, 'updateInfoUser']);
Route::put('admin/updateInfoCompany/{company_id}', [AdminController::class, 'updateInfoCompany']);
Route::put('admin/updateStateCompany/{company_id}', [AdminController::class, 'updateStateCompany']);
Route::delete('admin/daleteCompany/{company_id}', [AdminController::class, 'daleteCompany']);
Route::delete('admin/deleteUser/{user_id}', [AdminController::class, 'deleteUser']);

Route::post('adslog/storeAds', [AdsLogController::class, 'storeAds'])->middleware('ApprovedCompany');
Route::put('adslog/updateAds/{id}', [AdsLogController::class, 'updateAds'])->middleware('ApprovedCompany');
Route::delete('adslog/deleteAds/{id}', [AdsLogController::class, 'deleteAds'])->middleware('ApprovedCompany');
Route::get('adslog/showAdsLog/{id}', [AdsLogController::class, 'showAdsLog'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('admin/getAdminUnNotification', [AdminController::class, 'getAdminUnNotification']);
    Route::get('admin/getAdminAllNotification', [AdminController::class, 'getAdminAllNotification']);
});
/////////////////دوال لادارة السائقيين من قبل الشركة
Route::put('driver/acceptDriver/{id}',[DriverController::class,'acceptDriver'])->middleware('ApprovedCompany');
Route::post('driver/registerDriver/{id}', [DriverController::class, 'registerDriver'])->middleware('auth:sanctum');
Route::get('driver/showAllDriver', [DriverController::class, 'showAllDriver']);
Route::post('driver/stateDriver/{driver_id}', [DriverController::class, 'stateDriver']);
// Route::get('driver/delete', [DriverController::class, 'delete']);
//////////////vehicle
Route::post('vehicle/addBus',[VehicleController::class,'addBus'])->middleware('auth:sanctum');
Route::get('vehicle/countVehicle',[VehicleController::class,'countVehicle'])->middleware('ApprovedCompany');
Route::put('vehicle/stateVehicle/{id}',[VehicleController::class,'stateVehicle'])->middleware('ApprovedCompany');
Route::put('vehicle/updateVehicle/{id}',[VehicleController::class,'updateVehicle'])->middleware('ApprovedCompany');
Route::delete('vehicle/deleteVehicle/{id}',[VehicleController::class,'deleteVehicle'])->middleware('ApprovedCompany');
////////////////profile driver
Route::get('driver/showDriver/{id}', [DriverController::class,'showDriver']);
Route::put('driver/ubdateDocDriver', [ProfileDriverController::class, 'ubdateDocDriver'])->middleware('auth:sanctum');
// Route::put('driver/updateProfileDriver', [ProfileDriverController::class, 'updateProfileDriver'])->middleware('auth:sanctum');

///////Route for location
Route::post('driverLocation/store', [DriverLocationsController::class, 'store'])->middleware('auth:sanctum');
Route::get('driverLocation/show', [DriverLocationsController::class, 'show'])->middleware('auth:sanctum');
