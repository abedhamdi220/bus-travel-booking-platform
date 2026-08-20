<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\AdsLog;
use App\Models\Review;
use App\Models\Trip; // إضافة مودل الرحلات
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        // 1. جلب الشركات
        $companies = Company::select('id', 'name')->get();

        // 2. جلب العروض
        $ads = AdsLog::latest()->take(3)->get();

        // 3. جلب التقييمات
        $reviews = Review::latest()->take(3)->get();

        // 4. جلب المدن ديناميكياً من جدول الرحلات (لتجنب عرض مدن لا توجد لها رحلات فعلية)
        $departureCities = Trip::select('departure_city')->whereNotNull('departure_city')->distinct()->pluck('departure_city');
        $destinationCities = Trip::select('destination')->whereNotNull('destination')->distinct()->pluck('destination');

        return view('home', compact('companies', 'ads', 'reviews', 'departureCities', 'destinationCities'));
    }

    // 5. دالة معالجة فورم البحث الخاص بصفحة الهبوط
  public function search(Request $request)
    {
        // 1. نبدأ الاستعلام ونجلب علاقة الشركة
        $query = Trip::with('company');

        // فلترة بناءً على مدخلات المستخدم (إذا كان هناك بحث فعلي)
        if ($request->filled('departure_city')) {
            $query->where('departure_city', $request->departure_city);
        }
        if ($request->filled('destination')) {
            $query->where('destination', $request->destination);
        }
        if ($request->filled('dateTrip')) {
            $query->where('dateTrip', $request->dateTrip);
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // 2. جلب جميع الرحلات المطابقة (سواء قديمة أو جديدة)
        // 3. الترتيب: الأقرب تاريخاً أولاً، ثم نرتب حسب الوقت
        $trips = $query->orderBy('dateTrip', 'desc') // لجعل الرحلات الجديدة تظهر بشكل منطقي حسب التصميم
                       ->orderBy('timeTrip', 'asc')
                       ->get();

        // 4. ترتيب مخصص بالـ PHP (Collection) لفصل الرحلات المتاحة عن المنتهية
        // نجعل الرحلات المتاحة (تاريخها اليوم أو في المستقبل) تظهر في الأعلى
        $trips = $trips->sortByDesc(function ($trip) {
            return $trip->dateTrip >= now()->toDateString() ? 1 : 0;
        });

        return view('search', compact('trips'));
    }
}
