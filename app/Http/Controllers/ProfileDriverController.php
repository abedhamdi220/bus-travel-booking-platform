<?php

namespace App\Http\Controllers;

use App\Http\Requests\profileUpdateDriverRequest;
use App\Http\Resources\documentResoures;
use App\Http\Resources\DriverResource;
use App\Http\Resources\UserResource;
use App\Models\Document;
use App\Models\Driver;
use App\Models\ProfileDriver;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileDriverController extends Controller
{
    ////////////تعديل ملفات المستخدم
    public function ubdateDocDriver(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver();
        $request->validate([
            "file_CV" => "file|mimes:jpg,pdf,png|max:2048",
            "nationalPersonalImg" => "file|mimes:jpg,png|max:2048",
            "licenseImg" => "file|mimes:jpg,png|max:2048",
            "imgPersonale" => "file|mimes:jpg,png|max:2048",
        ]);
        $array = [
            "file_CV",
            "nationalPersonalImg",
            "licenseImg",
            "imgPersonale",
        ];
        foreach ($array as $file) {
            if ($request->hasFile($file)) {
                $oldPath = $driver->documents()->where('typeFile', $file)->first()->pathDoc;
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
                $newPath = $request->file($file)->store('documents', 'public');
                $driver->documents()->where('typeFile', $file)->update([
                    'pathDoc' => $newPath,
                    'typeFile' => $file
                ]);
            }
            $driver = $user->driver()->first();
            return response()->json([
                "message" => "update is done",
                "data" => $driver,
                'doc' => documentResoures::collection($driver->documents()->get())
            ], 200);
        }
    }
    public function updateProfileDriver(profileUpdateDriverRequest $request, int $id)
    {
        $data = $request->validated();
        $profile = ProfileDriver::findOrFail($id)->update($data);
        return response()->json([
            'message' => 'your information',
            "dtat" => $profile
        ], 200);
    }
    


}
