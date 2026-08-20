<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Document;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // 1. الدالة الجديدة لعرض لوحة التحكم
    public function index(Request $request)
    {
        $user = $request->user();

        // جلب الملف الشخصي والرحلات (الحجوزات) لعرضها في لوحة التحكم (تمت إزالة clone)
        $profile = $user->profile;
        $trips = $user->trip; // يعتمد على العلاقة في User.php

        return view('dashboard', compact('user', 'profile', 'trips'));
    }

    // 2. تعديل دالة تحديث الملف الشخصي
    public function update(ProfileUpdateRequest $request, int $id)
    {
        $user = $request->user();
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $pathImage = null;
        if($user->documents()->where('typeFile', 'imgPersonale')->exists()){
            $pathImage = Document::where('documentable_type', User::class)
                ->where('documentable_id', $user->id)
                ->where('typeFile', 'imgPersonale')->first()->pathDoc;
        }

        if ($request->hasFile('image')) {
            $result = DB::transaction(function () use ($request, $user) {
                $oldDocument = Document::where('documentable_type', User::class)
                    ->where('documentable_id', $user->id)
                    ->where('typeFile', 'imgPersonale')->first();

                if ($oldDocument) {
                    Storage::disk('public')->delete($oldDocument->pathDoc);
                    $oldDocument->delete();
                }

                $path = $request->file('image')->store('documents', 'public');
                $user->documents()->create([
                    'typeFile' => 'imgPersonale',
                    'pathDoc' => $path
                ]);
                return $path;
            });
            $pathImage = $result;
        }

        $profile = Profile::findOrFail($id);
        $profile->update($request->validated());

        // التوجيه مع رسالة نجاح بدلاً من إرجاع JSON
        return back()->with('success', 'تم تحديث بيانات ملفك الشخصي بنجاح.');
    }

    // 3. تعديل وتصحيح دالة تغيير كلمة المرور
    public function changePassword(Request $request)
    {
       $user = $request->user();
       $request->validate([
           'current_password' => 'required|current_password',
           'new_password' => 'required|string|min:8|confirmed'
       ]);

       // تحديث كلمة المرور بشكل صحيح مع التشفير
       $user->update([
           'password' => Hash::make($request->new_password)
       ]);

       return back()->with('success', 'تم تغيير كلمة المرور بنجاح ولأمانك.');
    }

    // 4. تعديل دالة حذف الحساب
    public function destroyAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password']
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم حذف حسابك بنجاح. نتمنى رؤيتك مجدداً.');
    }
}
