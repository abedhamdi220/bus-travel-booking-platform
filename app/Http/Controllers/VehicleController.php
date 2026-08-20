<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // 1. جلب جميع مركبات الشركة
public function getBuses()
    {
        $company = Auth::guard('company')->user();
        $vehicles = $company->vehicles()->latest()->get();

        $stats = [
            'total' => $vehicles->count(),
            'maintenance' => $vehicles->where('state', 'maintenance')->count(),
            'available' => $vehicles->where('state', 'availability')->count(),
            'on_trip' => $vehicles->where('state', 'on_trip')->count(),
        ];

        return view('company.buses', compact('vehicles', 'stats'));
    }

    // 2. إضافة مركبة جديدة
   public function addBus(Request $request)
    {
        $company = Auth::guard('company')->user();

        $data = $request->validate([
            "plate_number" => 'required|max:8|unique:vehicles,plate_number',
            "type_vehicle" => 'required|in:VIP,nurmal', // Keeping 'nurmal' to match your DB enum
            "state" => 'required|in:availability,maintenance,on_trip,out_of_service',
        ]);

        $data['date_work'] = now();

        // البحث عن أعلى رقم مركبة موجود لهذه الشركة، ثم إضافة 1
        $maxVehicleNumber = Vehicle::where('company_id', $company->id)->max('vehicle_number');
        $data['vehicle_number'] = $maxVehicleNumber ? $maxVehicleNumber + 1 : 1;

        $data['company_id'] = $company->id;

        Vehicle::create($data);

        return redirect()->route('company.buses')->with('success', 'New vehicle added to the fleet successfully.');
    }

    // 3. عدد المركبات الحالية للشركة
    public function countVehicle()
    {
        $company = Auth::guard('company')->user();
        $count = $company->vehicles()->count();
        return response()->json([
            "message" => "count vehicle",
            "count" => $count
        ], 200);
    }

    // 4. تعديل حالة المركبة
    public function stateVehicle(Request $request, int $id)
    {
        $data = $request->validate([
            'state' => 'in:active,inactive,maintenance,on_trip,out_of_service'
        ]);
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($data);
        return response()->json($vehicle, 200);
    }

  // 3. Delete a bus securely
    public function deleteVehicle(int $id)
    {
        $vehicle = Vehicle::where('company_id', Auth::guard('company')->id())->findOrFail($id);
        $vehicle->delete();

        return back()->with('success', 'Vehicle removed from the fleet successfully.');
    }

    // 6. تعديل معلومات المركبة
    public function updateVehicle(Request $request, int $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $data = $request->validate([
            "type_vehicle" => 'in:VIP,nurmal',
            "plate_number" => 'max:8|unique:vehicles,plate_number',
        ]);
        $vehicle->update($data);
        return response()->json([
            "message" => "update is done",
        ], 200);
    }

    // 7. عرض تفاصيل المركبة (مضافة لتعمل مع المسار)
    public function busDetailes($id) // Retained your method name mapping from web.php
    {
        $vehicle = Vehicle::where('company_id', Auth::guard('company')->id())->findOrFail($id);
        return view('company.bus-details', compact('vehicle'));
    }
}
