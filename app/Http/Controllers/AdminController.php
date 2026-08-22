<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Notifications\UpdateInfoCompanyNotification;
use App\Notifications\UpdateStateCompanyNotification;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    // 1. Admin Dashboard (Web)
    public function infoForWeb()
    {
        $countRegisterUser = User::count();
        $countTrivelCompany = Company::count();

        // Fetch companies and users for admin management
        $companies = Company::latest()->get();
        $users = User::latest()->get(); // إضافة جلب المستخدمين

        return view('admin-profile', compact('countRegisterUser', 'countTrivelCompany', 'companies', 'users'));
    }
    public function updateAdminProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|string|min:8|confirmed'
        ]);

        // تحديث الاسم والإيميل
        $admin->name = $request->name;
        $admin->email = $request->email;

        // تحديث كلمة المرور إذا تم إدخالها
        if ($request->filled('new_password')) {
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateInfoUser(Request $request, int  $user_id)
    {
        $user = User::find($user_id)->first();
        $user->update($request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|unique:users,email',
            'password' => 'string|min:8|confirmed',
            'role' => 'in:customer,admin,driver'
        ]));
        $user = User::find($user_id)->first();
        // Notification::send($user,new )
        return response()->json([
            'message' => 'update is done'
        ], 200);
    }

    public function updateStateCompany(Request $request, int $company_id)
    {
        $request->validate([
            'state' => 'required|in:Pending,Approved,Rejected' 
        ]);

        $company = Company::findOrFail($company_id);
        $company->update(['state' => $request->state]);

        // Notify Company
        Notification::send($company, new UpdateStateCompanyNotification($company));

        return back()->with('success', "Company state successfully updated to {$request->state}.");
    }

    // 6. Update Company Info (By Admin)
    public function updateInfoCompany(Request $request, int $company_id)
    {
        $company = Company::findOrFail($company_id);
        $data = $request->validate([
            'phone' => 'string|max:50',
            'name' => 'string|max:100',
            'address' => 'string|max:100',
            'email' => 'email|unique:companies,email,' . $company->id,
        ]);

        $company->update($data);
        Notification::send($company, new UpdateInfoCompanyNotification($company));

        return back()->with('success', 'Company information updated successfully.');
    }

    // 4. Delete User
    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User account deleted successfully.');
    }

    public function blockUser(int $id)
    {
        $user = User::findOrFail($id);
        $user->state = 'blocked';
        $user->save();

        // تمت إضافة إعادة التوجيه لكي تعمل الدالة بشكل صحيح في المتصفح
        return back()->with('success', 'تم حظر المستخدم بنجاح.');
    }

    // 3. Delete Company
    public function daleteCompany(int $id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return back()->with('success', 'Company deleted successfully from the system.');
    }

    public function getAdminUnNotification()
    {
        $notifications = Auth::user()->unreadNotifications;
        return response()->json([
            'massage' => 'notification Admin ',
            $notifications
        ], 200);
    }

    public function getAdminAllNotification()
    {
        $notifications = Auth::user()->Notifications;
        return response()->json([
            'massage' => 'notification Admin ',
            $notifications
        ], 200);
    }

    //////جلب جميع الشركات المقبولة
    private function getCompanies()
    {
        return Company::where('state', 'Approved')->get();
    }

    public function index()
    {
        $companies = $this->getCompanies();
        return view('home', compact('companies'));
    }
}
