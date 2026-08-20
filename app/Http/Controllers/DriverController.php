<?php

namespace App\Http\Controllers;

use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Models\User;
use App\Notifications\suspendedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class DriverController extends Controller
{

    public function getCompanyDrivers()
    {
        $company_id = Auth::guard('company')->id();

        // Fetch drivers with their associated user data
        $drivers = Driver::with('user')->where('company_id', $company_id)->latest()->get();

        // Calculate Stats for the UI
        $stats = [
            'total' => $drivers->count(),
            'approved' => $drivers->where('state', 'Approved')->count(),
            'pending' => $drivers->where('state', '!=', 'Approved')->count(),
        ];

        return view('company.mydrivers', compact('drivers', 'stats'));
    }

  public function registerDriver(Request $request)
    {
        $company_id = Auth::guard('company')->id();

        $data = $request->validate([
            "name" => 'required|string|max:255',
            "email" => 'required|email|unique:users,email',
            "password" => 'required|min:8',
            "phone" => 'required|numeric',
            'birthdate' => 'required|date',
            "file_CV" => "required|file|mimes:pdf,jpg,png|max:2048",
            "nationalPersonalImg" => "required|file|mimes:jpg,png|max:2048",
            "licenseImg" => "required|file|mimes:jpg,png|max:2048",
            "imgPersonale" => "required|file|mimes:jpg,png|max:2048",
        ]);

        DB::transaction(function () use ($data, $request, $company_id) {
            // Create User Account for the Driver
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                'role' => 'driver',
                'joined' => now(),
            ]);

            // Create Driver Record
            $driver = Driver::create([
                'phone' => $data['phone'],
                'birthdate' => $data['birthdate'],
                'company_id' => $company_id,
                'user_id' => $user->id,
                'state' => 'Approved',
                'availability_state' => 'availability'
            ]);

            // Upload Documents
            $files = [
                'file_CV' => 'CV',
                'nationalPersonalImg' => 'nationalPersonalImg',
                'licenseImg' => 'licenseImg',
                'imgPersonale' => 'imgPersonale'
            ];

            foreach ($files as $fileKey => $typeFile) {
                if ($request->hasFile($fileKey)) {
                    $filePath = $request->file($fileKey)->store('documents', 'public');
                    $driver->documents()->create([
                        'pathDoc' => $filePath,
                        'typeFile' => $typeFile,
                    ]);
                }
            }
        });

        return redirect()->route('company.mydrivers')->with('success', 'Driver registered and added to your fleet successfully.');
    }

    // عرض بيانات سائق
    public function showDriver(int $id)
    {
        $driver = Driver::with('user')->findOrFail($id);
        return response()->json([
            "message" => 'the driver',
            "data" => DriverResource::make($driver),
        ], 200);
    }

    // عرض جميع السائقين في النظام
    public function showAllDriver()
    {
        $driver = Driver::with('user')->get();
        return response()->json([
            "message" => 'all drivers',
            "data" => DriverResource::collection($driver)
        ], 200);
    }

    // تعديل حالة السائق
    public function stateDriver(Request $request, int $id)
    {
        $data = $request->validate([
            'state' => 'in:suspended,Approved,Rejected',
            'reason' => 'required_if:state,suspended|string|max:1000'
        ]);

        $driver = Driver::findOrFail($id);
        $driver->state = $data['state'];
        $driver->save();

        if ($data['state'] == 'suspended') {
            $reason = $data['reason'];
            Notification::send($driver, new suspendedNotification($reason));
        }

        return response()->json(["message"=>'driver state updated successfully'], 200);
    }

    // تحديث: حذف سائق تابع للشركة وتسريحه
    // 3. Delete / Dismiss Driver
    public function delete(int $id)
    {
        $company_id = Auth::guard('company')->id();
        $driver = Driver::where('company_id', $company_id)->findOrFail($id);

        $driver->delete();

        return back()->with('success', 'Driver has been dismissed successfully.');
    }

    // قبول السائق
    public function acceptDriver(Request $request, int $id)
    {
        $driver = Driver::findOrFail($id);
        $data = $request->validate([
            "state" => "in:Pending,Approved,Rejected"
        ]);
        $driver->update($data);
        return response()->json($driver, 200);
    }
}
