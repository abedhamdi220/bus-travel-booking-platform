<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Trip;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


use function Symfony\Component\Clock\now;

class PaymentController extends Controller
{
  public function paysham(Request $request)
    {
        $data = $request->validate([
            'tripNumber' => 'required'
        ]);

        $cust_id = Auth::id();

        try {
            $cost = \App\Models\Trip::findOrFail($data['tripNumber'])->cost;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'الرحلة غير موجودة في قاعدة البيانات.']);
        }

        try {
            // الاتصال ببوابة شام كاش
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.shamcash.key'),
                'Accept' => 'application/json',
            ])->post('https://api.shamcash.com/v1/payments', [
                'amount' => $cost,
                'customer_id' => $cust_id,
                'order_id' => 'TRIP_' . $data['tripNumber'] . '_' . time(),
                'callback_url' => url('/payment/callback'), // تعديل مسار الرد ليناسب Web
                'description' => 'دفع قيمة الرحلة ' . $data['tripNumber'],
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['payment_url'])) {
                    // التوجيه الفعلي خارج التطبيق إلى بوابة الدفع
                    return redirect()->away($result['payment_url']);
                }
            }
            throw new \Exception("لم يتم استلام رابط الدفع من البوابة.");

        } catch (\Exception $e) {
            // التوجيه إلى لوحة تحكم Blade مع رسالة خطأ بدلاً من localhost:3000
            return redirect()->route('dashboard')->with('error', 'فشل الاتصال ببوابة الدفع. يرجى المحاولة لاحقاً.');
        }
    }

// دالة مبدئية لاستقبال رد بوابة الدفع (Callback)
public function callback(Request $request)
{
    // هنا يتم كتابة كود تحديث حالة الحجز إلى "مدفوع" بناءً على رد ShamCash
    return response()->json(['message' => 'تم استلام الرد من البوابة']);
}
public function showPayment($tripId)
    {
        $trip = \App\Models\Trip::findOrFail($tripId);
        $cost = $trip->cost;
        return view('payment.show', compact('tripId', 'cost'));
    }
}
