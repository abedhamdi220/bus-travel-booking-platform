<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // تقييم الشركة
    public function ratingCompany(Request $request)
    {
        $user_id = Auth::user()->id;
        $company_id = $request->company_id;
        $company = Company::findOrFail($company_id);
        $data = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);
        if (empty($data['comment'])) {
            $data['comment'] = match ((int)$data['rating']) {
                5 => "تجربة ممتازة وأنصح بالتعامل معهم",
                4 => "خدمة جيدة جدا راض عن التجربة",
                3 => "تجربة جيدا بشكل عام",
                2 => "الخدمة كانت اقل من المتوقع",
                1 => "للأسف تجربة سيئة ولا أنصح بهم",
                default => "unknown"
            };
        }
        if ($company->reviews()->where('user_id', Auth::user()->id)->exists()) {
            $company->reviews()->where('user_id', Auth::user()->id)->update($data);
            return response()->json([
                'message' => "new rating is $request->rating",
            ], 200);
        }
        $data['user_id'] = $user_id;
        $review = $company->reviews()->create($data);
        return back()->with('success', 'تم إرسال تقييمك بنجاح. شكراً لثقتك بنا!');
    }
    ///////////تقييم السائق
    public function ratingDriver(Request $request, int $id)
    {
        $user_id = Auth::user()->id;
        $driver = Driver::findOrFail($id);
        $data = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);
        if (empty($data['comment'])) {
            $data['comment'] = match ((int)$data['rating']) {
                5 => "تجربة ممتازة و إنه سائق محترف",
                4 => "سائق جيد جدا راض عن التجربة",
                3 => " جيد بشكل عام",
                2 => "السائق كان سيئ قليلا",
                1 => "للأسف تجربة سيئة ولا أنصح بالذهاب معه",
                default => "unknown"
            };
        }
        if ($driver->reviews()->where('user_id', Auth::user()->id)->exists()) {
            $driver->reviews()->where('user_id', Auth::user()->id)->update($data);
            return response()->json([
                'message' => "new rating is $request->rating",
            ], 200);
        }
        $data['user_id'] = $user_id;
        $review = $driver->reviews()->create($data);
        return response()->json([
            'message' => 'rating is done',
            $review
        ], 200);
    }
    ///////////جلب معدل تقييم السائقين
    public function getAvgDriver(int $id)
    {
        $driver = Driver::findOrFail($id);
        $AvgRating = $driver->reviews()->avg('rating');
        return response()->json($AvgRating, 200);
    }
    /////////عدد مقيمين السائق
    public function countReviewsDriver(int $id)
    {
        $driver = Driver::findOrFail($id);
        $countReviews = $driver->reviews()->count('rating');
        return response()->json($countReviews, 200);
    }
    public function getAvgCompany(int $id)
    {
        $company = Company::findOrFail($id);
        $AvgRating = $company->reviews()->avg('rating');

        return response()->json($AvgRating, 200);
    }

    /////////عدد مقيمين الشركة
    public function countReviewsCompany(int $id)
    {
        $company = Company::findOrFail($id);
        $countReviews = $company->reviews()->count('rating');

        return response()->json($countReviews, 200);
    }
}
