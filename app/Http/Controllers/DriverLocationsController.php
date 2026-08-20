<?php

namespace App\Http\Controllers;

use App\Models\DriverLocations;
use Auth;
use Illuminate\Http\Request;

class DriverLocationsController extends Controller
{       ///////// تخزين موقع السائق
        public function store(Request $request)
        {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'driver_id' => 'required|exists:drivers,id',
            ]);

            // Create a new driver location record
            $driverLocation = DriverLocations::updateOrCreate($validatedData);

            return response()->json([
                'message' => 'Driver location stored successfully',
                'data' => $driverLocation
            ], 201);
        }
        /////عرض موقع السائق لصاحب الشركة
        public function show(){
            $company_id=Auth::guard('sanctum')->user()->id;
            $drivers_locations=DriverLocations::whereHas('driver',function($q) use($company_id){
                $q->where('company_id',$company_id);
            })->with('driver.user')->get();

            return response()->json([
                'data' => $drivers_locations
            ]);
        }
        //////////عرض موقع الباص الحالي للسائقين على متنه

}
