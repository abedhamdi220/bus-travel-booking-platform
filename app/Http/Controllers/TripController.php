<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Models\Company;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{

    public function search(Request $request,int $company_id)
    {
        $data = $request->validate([
            'dateTrip' => 'date',
            'timeTrip' => 'date_format:H:i',
            'departure_city' => 'string|max:50',
            'destination' => 'string|max:50',
        ]);
        $company = Company::findOrFail($company_id);
        $trips = $company->trips()
            ->where('dateTrip', $data['dateTrip'])
            ->where('timeTrip', $data['timeTrip'])
            ->where('departure_city', $data['departure_city'])
            ->where('destination', $data['destination'])->get();
        return response()->json([
            'trips' => $trips
        ], 200);
    }
}
