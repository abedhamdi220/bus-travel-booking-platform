<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Company;
use App\Models\Seats; // تأكد من أن اسم الموديل Seats بصيغة الجمع كما أشرت في كودك
use App\Models\Trip;
use App\Notifications\BookingNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    use AuthorizesRequests;

    // 1. جلب حجوزات الشركة

public function showCompanyBookings()
    {
        $company_id = Auth::guard('company')->id();

        $bookings = DB::table('bookings')
            ->join('trips', 'bookings.trip_id', '=', 'trips.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('trips.company_id', $company_id)
            ->select(
                'bookings.id as booking_id',
                'bookings.dateBooking',
                'bookings.seat_id',
                'users.name as passenger_name',
                'trips.departure_city',
                'trips.destination',
                'trips.dateTrip',
                'trips.timeTrip',
                'trips.cost'
            )
            ->orderBy('bookings.dateBooking', 'desc')
            ->get();

        return view('company.company-bookings', compact('bookings'));
    }
    // 2. دالة الحجز للمستخدم العادي (كما هي من طرفك)
    public function booking(Request $request, int $tripId)
    {
        $data = $request->validate([
            'bookings' => 'required|array',
            'bookings.*.seatNum' => 'required|integer', // تأكد من أنه رقم
            'bookings.*.gender' => 'required|in:male,female',
            'bookings.*.phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $userId = $user->id;
        $trip = Trip::findOrFail($tripId);

        // 1. تعريف مصفوفة لتخزين الحجوزات الناجحة لإرسالها في الإشعارات لاحقاً
        $successfulBookings = [];

        try {
            DB::transaction(function () use ($request, $tripId, $userId, &$successfulBookings) {

                foreach ($request->bookings as $bookingdata) {
                    $seatNum = $bookingdata['seatNum'];
                    $gender = $bookingdata['gender'];
                    $phone = $bookingdata['phone'];

                    // استخدم lockForUpdate لمنع حجز نفس المقعد في نفس اللحظة من مستخدمين مختلفين (Race Condition)
                    $seat = Seats::where('number_seat', $seatNum)
                        ->where('trip_id', $tripId)
                        ->lockForUpdate()
                        ->first();

                    if (!$seat) {
                        throw new \Exception("المقعد رقم {$seatNum} غير موجود في هذه الرحلة.");
                    }

                    if ($seat->state == 'booked') {
                        throw new \Exception("المقعد رقم {$seatNum} محجوز مسبقاً ولا يمكن حجزه مجدداً.");
                    }

                    // تحديث المقعد
                    $seat->update([
                        'user_id' => $userId,
                        'state' => 'booked',
                    ]);

                    // إنشاء سجل الحجز
                    // إنشاء سجل الحجز
                    $booking = Booking::create([
                        'dateBooking' => now(),
                        'trip_id' => $tripId,
                        'user_id' => $userId,
                        'seat_id' => $seat->id,
                        'gender' => $gender, // تأكد من استخدام gender هنا
                        'phone' => $phone,   // وتأكد من إرسال الهاتف
                    ]);

                    // تخزين الحجز في المصفوفة
                    $successfulBookings[] = $booking;
                }
            });

            // ==========================================
            // 2. إرسال الإشعارات *بعد* نجاح الـ Transaction
            // ==========================================
            $company = $trip->company;

            foreach ($successfulBookings as $booking) {
                Notification::send($user, new BookingNotification($booking));
                if ($company) {
                    Notification::send($company, new BookingNotification($booking));
                }
            }
            return redirect()->route('payment.show', ['tripId' => $tripId])
                ->with('success', 'تم اختيار المقاعد مبدئياً، يرجى إتمام الدفع لتأكيد الحجز.');
            // return response()->json([
            //     "success" => true,
            //     "message" => "Booking completed successfully for " . count($successfulBookings) . " seats."
            // ], 200);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
            // catch (\Exception $e) {
            //     return response()->json([
            //         "success" => false,
            //         "message" => $e->getMessage()
            //     ], 400);
        }
    }
    public function cancelBooking(int $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $user_id = Auth::id();

        // التأكد من أن الحجز يعود للمستخدم المسجل الدخول
        if ($booking->user_id != $user_id) {
            return response()->json(['message' => 'Unauthorized. This booking does not belong to you.'], 403);
        }

        // تحرير المقعد ليعود متاحاً للحجز
        Seats::where('number_seat', $booking->seat_id)
            ->where('trip_id', $booking->trip_id)
            ->update(['state' => 'avaliable', 'user_id' => null]);

        // حذف الحجز
        $booking->delete();

        return response()->json(["message" => "Booking cancelled successfully by user"], 200);
    }
    // 2. Company Cancel Booking
    public function companyCancelBooking(int $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $trip = Trip::findOrFail($booking->trip_id);
        $company_id = Auth::guard('company')->id();

        if ($trip->company_id != $company_id) {
            abort(403, 'Unauthorized action.');
        }

        Seats::where('number_seat', $booking->seat_id)
            ->where('trip_id', $trip->id)
            ->update(['state' => 'avaliable', 'user_id' => null]);

        $booking->delete();

        return back()->with('success', 'Booking cancelled and seat released successfully.');
    }
    // 3. إلغاء الحجز من طرف الشركة
    // public function companyCancelBooking(int $bookingId)
    // {
    //     $booking = Booking::findOrFail($bookingId);
    //     $trip = Trip::findOrFail($booking->trip_id);
    //     $company_id = Auth::guard('company')->id();

    //     // التأكد من أن الرحلة تابعة للشركة المسجلة الدخول
    //     if ($trip->company_id != $company_id) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     // تحرير المقعد ليعود متاحاً للحجز
    //     Seats::where('number_seat', $booking->seat_id)
    //         ->where('trip_id', $trip->id)
    //         ->update(['state' => 'avaliable', 'user_id' => null]);

    //     // حذف الحجز
    //     $booking->delete();

    //     return response()->json(["message" => "Booking cancelled successfully"], 200);
    // }
    public function showSeatSelection($tripId)
    {
        // جلب الرحلة مع مقاعدها
        $trip = \App\Models\Trip::with('seats')->findOrFail($tripId);

        return view('booking.seats', compact('trip'));
    }
}
