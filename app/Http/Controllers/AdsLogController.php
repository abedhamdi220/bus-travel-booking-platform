<?php

namespace App\Http\Controllers;

use App\Models\AdsLog;
use App\Models\Company;
use App\Notifications\AdsLogNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AdsLogController extends Controller
{
    // 1. Show Offers & News View (Replaces API fetch)
    public function index()
    {
        $company = Auth::guard('company')->user();
        $ads = $company->myAdsLog()->latest()->get(); // Assuming relationship is defined in Company model

        return view('company.offers', compact('ads'));
    }
   public function storeAds(Request $request)
    {
        $company = Auth::guard('company')->user();

        $data = $request->validate([
            'news' => 'required|string|max:500',
        ]);

        $data['company_id'] = $company->id;
        $adslog = AdsLog::create($data);

        // Notify Admins
        $admin = \App\Models\User::where('role', 'admin')->first();
        if($admin) {
            Notification::send($admin, new AdsLogNotification($adslog));
        }

        return back()->with('success', 'Your offer/news has been published successfully.');
    }
   // 3. Update existing Offer
    public function updateAds(Request $request, int $id_ads)
    {
        $company = Auth::guard('company')->user();
        $data = $request->validate([
            'news' => 'required|string|max:500',
        ]);

        $ads = AdsLog::where('company_id', $company->id)->findOrFail($id_ads);
        $ads->update($data);

        return back()->with('success', 'Offer updated successfully.');
    }
   // 4. Delete Offer
    public function deleteAds(int $ads_id)
    {
        $company = Auth::guard('company')->user();
        $ads = AdsLog::where('company_id', $company->id)->findOrFail($ads_id);

        $ads->delete();

        return back()->with('success', 'Offer deleted permanently.');
    }

    public function showAdsLog(int $id)
    {
        $company = Company::findOrFail($id)->myAdsLog;
        return response()->json([
            'message' => 'the adsLog',
            'data' => $company
        ], 200);
    }
}
