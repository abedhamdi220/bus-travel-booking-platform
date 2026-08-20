<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Profile;
use App\Models\ProfileCompany;
use App\Models\User;
use App\Notifications\CompanyNotification;
use App\Notifications\RegisterUserNotification;
use DB;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Validator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class UserController extends Controller
{
    // =========================================================================
    // 1. دوال الـ API (المخصصة للموبايل أو Postman - تعيد Token)
    // =========================================================================

    public function registerApi(Request $request)
    {
        // (تم إبقاء هذه الدالة كما هي بناءً على طلبك بعدم التأثير على API)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|unique:companies,email',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required_if:role,user|in:male,female|nullable',
            'logo' => 'required_if:role,company|nullable|image|mimes:jpg,png,jpeg|max:2024|nullable',
            'infoCompany' => 'required_if:role,company|file|mimes:pdf,doc,docx|max:4096|nullable',
            'phone' => 'required_if:role,company|string|max:20|nullable',
            'address' => 'required_if:role,company|string|max:100|nullable',
        ], [
            // رسائل الخطأ...
            'name.required' => 'يجب ان يكون الاسم غير فارغ',
            'name.max' => 'يجب ان يكون الاسم اقل من 255 حرف',
            'email.required' => 'يجب ان يكون الايميل غير فارغ',
            'email.unique' => 'هذا الايميل مسجل مسبقاً',
            'email.email' => 'صيغة الايميل غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'يجب ان تكون كلمة المرور من 8 محارف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'gender.required_if' => 'الرجاء اختيار الجنس',
            'gender.in' => 'الجنس يجب ان يكون male أو female',
            'address.required_if' => 'عنوان الشركة مطلوب',
            'phone.required_if' => 'رقم الهاتف مطلوب',
            'logo.required_if' => 'شعار الشركة مطلوب',
            'logo.image' => 'يجب أن يكون الملف صورة',
            'logo.mimes' => 'نوع الصورة يجب أن يكون JPG أو PNG',
            'logo.max' => 'حجم الصورة لا يتجاوز 2 ميجابايت',
            'infoCompany.required_if' => 'المستندات القانونية مطلوبة',
            'infoCompany.mimes' => 'يجب أن يكون الملف PDF أو JPG أو PNG',
            'infoCompany.max' => 'حجم الملف لا يتجاوز 5 ميجابايت',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تصحيح الأخطاء',
                'errors' => $validator->errors(),
            ], 422);
        }

        ////////////////////////////////////////////////register company API
        if ($request->role == 'company') {
            try {
                $token = null;
                $company = null;

                DB::transaction(function () use ($request, &$company, &$token) {
                    $company = Company::create([
                        'phone' =>  $request->phone,
                        'name' =>  $request->name,
                        'address' =>  $request->address,
                        'email' =>  $request->email,
                        'password' => Hash::make($request->password),
                        'state' => 'Pending'
                    ]);

                    $token = $company->createToken('company_api_token')->plainTextToken;

                    ProfileCompany::create([
                        'phone' => $request->phone,
                        'name' =>  $request->name,
                        'address' =>  $request->address,
                        'email' => $request->email,
                        'company_id' => $company->id,
                    ]);

                    if ($request->hasFile('infoCompany') || $request->hasFile('logo')) {
                        $docs = [];
                        if ($request->hasFile('infoCompany')) {
                            $docs[] = [
                                'typeFile' => "infoCompany",
                                'pathDoc' =>  $request->infoCompany->store('documents', 'public')
                            ];
                        }
                        if ($request->hasFile('logo')) {
                            $docs[] = [
                                'typeFile' => "logoCompany",
                                'pathDoc' => $request->logo->store('documents', 'public')
                            ];
                        }
                        if (!empty($docs)) {
                            $company->documents()->createMany($docs);
                        }
                    }

                    if ($request->hasFile('logo')) {
                        $image = $request->logo;
                        $filename = time() . '_' . $image->getClientOriginalName();
                        $directory = public_path('documents');
                        if (!is_dir($directory)) {
                            mkdir($directory, 0755, true);
                        }
                        $path = $directory . DIRECTORY_SEPARATOR . $filename;
                        Image::decode($image->getRealPath())->cover(100, 100)->save($path);
                    }

                    $admin = User::where('role', 'admin')->first();
                    if ($admin) {
                        Notification::send($admin, new CompanyNotification($company));
                    }
                });

                return response()->json([
                    'success' => true,
                    'message' => 'تم التسجيل بنجاح، الحساب بانتظار موافقة الإدارة.',
                    'company' => $company,
                    'token' => $token
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء التسجيل',
                    'errors' => $e->getMessage()
                ], 500);
            }
        }
        ////////////////////////////////////////////////register user API
        else {
            try {
                $token = null;
                $user = null;

                DB::transaction(function () use ($request, &$user, &$token) {
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'joined' => now(),
                        'password' => Hash::make($request->password),
                        'gender' => $request->gender,
                    ]);

                    $token = $user->createToken('user_api_token')->plainTextToken;

                    Profile::create([
                        'user_id' => $user->id,
                        'name' => $request->name,
                        'email' => $request->email,
                    ]);

                    event(new Registered($user));

                    $admin = User::where('role', 'admin')->get();
                    if ($admin->count() > 0) {
                        Notification::send($admin, new RegisterUserNotification($user));
                    }
                });

                return response()->json([
                    'success' => true,
                    'message' => 'تم التسجيل بنجاح',
                    'user' => $user,
                    'token' => $token
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء التسجيل',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function loginApi(Request $request)
    {
        // (تم إبقاء هذه الدالة كما هي بناءً على طلبك بعدم التأثير على API)
        $validator = Validator::make(
            $request->all(),
            [
                "role" => "required|in:user,company",
                "email" => "required|email",
                "password" => "required",
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تصحيح الأخطاء',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->role == "user") {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الايميل او كلمة المرور خاطئة',
                ], 401);
            }

            $token = $user->createToken('user_api_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            $company = Company::where('email', $request->email)->first();

            if (!$company || !Hash::check($request->password, $company->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الايميل او كلمة المرور خاطئة',
                ], 401);
            }

            $token = $company->createToken('company_api_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'company' => $company,
                'token' => $token
            ], 200);
        }
    }

    // =========================================================================
    // 2. دوال الـ WEB (المخصصة للمتصفح - تعتمد على Redirect و Blade)
    // =========================================================================

   public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required_if:role,user|in:male,female|nullable',
            'logo' => 'required_if:role,company|nullable|image|mimes:jpg,png,jpeg|max:2024|nullable',
            'infoCompany' => 'required_if:role,company|file|mimes:pdf,doc,docx|max:4096|nullable',
            'role' => 'in:user,company',
            'phone' => 'required_if:role,company|string|max:20|nullable',
            'address' => 'required_if:role,company|string|max:100|nullable',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role.in' => 'Invalid account type selected.',
            'logo.required_if' => 'Company logo is required.',
            'infoCompany.required_if' => 'Legal documents are required for companies.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // =======================================================
        // 1. Registration Logic for COMPANIES
        // =======================================================
        if ($request->role == 'company') {
            try {
                DB::transaction(function () use ($request) {
                    $company = Company::create([
                        'phone' =>  $request->phone,
                        'name' =>  $request->name,
                        'address' =>  $request->address,
                        'email' =>  $request->email,
                        'password' => Hash::make($request->password),
                        'state' => 'Pending'
                    ]);

                    ProfileCompany::create([
                        'phone' => $request->phone,
                        'name' =>  $request->name,
                        'address' =>  $request->address,
                        'email' => $request->email,
                        'company_id' => $company->id,
                    ]);

                    $company->documents()->createMany([
                        [
                            'typeFile' => "infoCompany",
                            'pathDoc' =>  $request->infoCompany->store('documents', 'public')
                        ],
                        [
                            'typeFile' => "logoCompany",
                            'pathDoc' => $request->logo->store('documents', 'public'),
                        ]
                    ]);

                    $admin = User::where('role', 'admin')->first();
                    if ($admin) {
                        Notification::send($admin, new CompanyNotification($company));
                    }
                });

                // 🟢 التوجيه المعماري الصحيح للويب بدلاً من الـ JSON
                return redirect()->route('login')->with('success', 'Company registered successfully. Please wait for admin approval.');

            } catch (Exception $e) {
                return back()->with('error', 'An error occurred during registration: ' . $e->getMessage())->withInput();
            }
        }

        // =======================================================
        // 2. Registration Logic for PASSENGERS (Users)
        // =======================================================
        else {
            try {
                DB::transaction(function () use ($request) {
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'joined' => now(),
                        'password' => Hash::make($request->password),
                        'gender' => $request->gender,
                        // 🔴 التعديل هنا: استخدام customer بدلاً من user لتطابق قاعدة البيانات
                        'role' => 'customer'
                    ]);

                    Profile::create([
                        'user_id' => $user->id,
                        'name' => $request->name,
                        'email' => $request->email,
                    ]);

                    event(new Registered($user));

                    $admin = User::where('role', 'admin')->first();
                    if ($admin) {
                        Notification::send($admin, new RegisterUserNotification($user));
                    }

                    Auth::login($user);
                });

                return redirect()->route('landing')->with('success', 'Account created and logged in successfully.');

            } catch (Exception $e) {
                return back()->with('error', 'An error occurred during registration: ' . $e->getMessage())->withInput();
            }
        }
    }
  public function login(Request $request)
    {
        // 1. Validation (Full English)
        $validator = Validator::make(
            $request->all(),
            [
                "role" => "required|in:user,company,admin",
                "email" => "required|email|max:255",
                "password" => "required|min:8",
            ],
            [
                'email.required' => 'The email address is required.',
                'email.email' => 'Please provide a valid email address.',
                'password.required' => 'The password is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'role.in' => 'Invalid account type selected.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email', 'role'));
        }

        $remember = $request->boolean('remember');

       // =======================================================
        // 2. Login Logic for PASSENGERS & ADMINS (Web Guard)
        // =======================================================
        if ($request->role === "user" || $request->role === "admin") {

            $remember = $request->boolean('remember');

            if (Auth::attempt($request->only("email", "password"), $remember)) {
                $request->session()->regenerate();

                $user = Auth::user();

                // 💡 الحل الجذري: قراءة الدور بغض النظر عن حالة الأحرف واسم العمود
                $actualRole = strtolower($user->role ?? '');

                // 🛑 SECURITY CHECK: Block standard users trying to login as admin
                if ($request->role === 'admin' && $actualRole !== 'admin') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->with('error', 'Access Denied: You do not have administrator privileges.')->onlyInput('email', 'role');
                }

                // 🟢 Failsafe Routing based on ACTUAL role
                if ($actualRole === 'admin') {
                    return redirect()->route('admin.dashboard')->with('success', 'Welcome back to the Command Center, Super Admin.');
                }

                return redirect()->intended('/')->with('success', 'Welcome back to Al-Sham Fleet.');
            } else {
                return back()->withErrors([
                    'email' => 'Invalid email or password credentials.',
                ])->onlyInput('email', 'role');
            }
        }


        // =======================================================
        // 3. Login Logic for COMPANIES (Company Guard)
        // =======================================================
        else {
            if (Auth::guard('company')->attempt($request->only("email", "password"), $remember)) {
                $request->session()->regenerate(); // 🛡️ Prevent Session Fixation

                $company = Auth::guard('company')->user();

                if ($company->state === "Approved") {
                    return redirect()->route('company.dashboard')->with('success', 'Logged in successfully.');
                } else {
                    // Company is pending/suspended -> limited access
                    return redirect()->route('company.profile')->with('success', 'Your account is pending approval. Limited access granted.');
                }
            } else {
                return back()->withErrors([
                    'email' => 'Invalid email or password credentials.',
                ])->onlyInput('email', 'role');
            }
        }
    }

    public function logout(Request $request)
    {
        // تسجيل الخروج بناءً على الجارد النشط
        if(Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    // =========================================================================
    // 3. دوال إعادة تعيين كلمة المرور
    // =========================================================================

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'in:user,company'],
        ]);

        $role = $request->role === 'company' ? 'companies' : 'users';

        $status = Password::broker($role)->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            // تعديل: يمكن أن تعيد Redirect بدلاً من JSON حسب ما بنيناه في الـ Blade
            return back()->with('success', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
        }

        return back()->withErrors(['email' => __($status)])->withInput();
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:user,company',
        ]);

        $role = $request->role === 'company' ? 'companies' : 'users';

        $status = Password::broker($role)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/login')->with('success', 'تم إعادة تعيين كلمة المرور بنجاح. يمكنك الآن تسجيل الدخول.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }
}
