@php
    $company = \Illuminate\Support\Facades\Auth::guard('company')->user();
    $logo = $company ? $company->documents()->where('typeFile', 'logoCompany')->first() : null;
@endphp

<aside class="w-full lg:w-1/4 shrink-0 hidden lg:block">
    <!-- 💡 التعديل هنا: تغيير sticky top-24 إلى top-28 لتنزل قليلاً تحت النافبار -->
    <div class="bg-slate-900/95 backdrop-blur-xl border border-slate-700 shadow-2xl rounded-3xl p-6 sticky top-28 text-slate-300">

        <!-- معلومات الشركة والشعار -->
        <div class="flex flex-col items-center mb-8 pb-8 border-b border-slate-700/60">
            <div class="relative w-28 h-28 rounded-full bg-gradient-to-tr from-amber-400 to-amber-600 p-1 mb-4 shadow-lg">
                <div class="w-full h-full bg-slate-900 rounded-full flex items-center justify-center overflow-hidden bg-cover bg-center"
                     @if($logo) style="background-image: url('{{ asset('storage/' . $logo->pathDoc) }}');" @endif>
                    @if(!$logo) <i class="fas fa-building text-3xl text-amber-500"></i> @endif
                </div>
            </div>
            <h2 class="text-xl font-bold text-white text-center">{{ $company->name ?? 'Company Name' }}</h2>
            <span class="text-xs text-amber-500 mt-1 uppercase tracking-widest font-semibold"><i class="fas fa-check-circle"></i> Official Partner</span>
        </div>

        <!-- الروابط الديناميكية -->
        <nav class="space-y-2 w-full mt-2">
            <!-- 1. لوحة التحكم -->
            <a href="{{ route('company.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('company.dashboard') ? 'bg-amber-500 text-slate-900 shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition-all">
                <i class="fas fa-tachometer-alt w-5 text-center"></i> Dashboard
            </a>

            <!-- 2. الملف الشخصي -->
            <a href="{{ route('company.profile') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('company.profile') ? 'bg-amber-500 text-slate-900 shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition-all">
                <i class="fas fa-building w-5 text-center"></i> Profile Settings
            </a>

            <!-- 3. إدارة الرحلات -->
            <a href="{{ route('company.trips') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('company.trips', 'company.addTrip') ? 'bg-amber-500 text-slate-900 shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition-all">
                <i class="fas fa-route w-5 text-center"></i> Manage Trips
            </a>

            <!-- 4. الحجوزات -->
            <a href="{{ route('company.bookings') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('company.bookings') ? 'bg-amber-500 text-slate-900 shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition-all">
                <i class="fas fa-ticket-alt w-5 text-center"></i> Reservations
            </a>

            <!-- 5. الأسطول والحافلات -->
            <a href="{{ route('company.buses') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('company.buses', 'company.add-bus', 'company.buses.details') ? 'bg-amber-500 text-slate-900 shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition-all">
                <i class="fas fa-bus-alt w-5 text-center"></i> Fleet & Buses
            </a>

            <!-- 6. الموارد البشرية (السائقين) -->
            <a href="{{ route('company.mydrivers') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('company.mydrivers', 'company.drivers.create') ? 'bg-amber-500 text-slate-900 shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition-all">
                <i class="fas fa-id-card w-5 text-center"></i> Drivers (HR)
            </a>

            <!-- 7. العروض والأخبار -->
            <a href="{{ route('company.offers') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('company.offers') ? 'bg-amber-500 text-slate-900 shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition-all">
                <i class="fas fa-bullhorn w-5 text-center"></i> Offers & News
            </a>

            <!-- خط فاصل -->
            <div class="border-t border-slate-700/60 my-4 w-full"></div>

            <!-- 8. تسجيل الخروج -->
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-red-400 hover:bg-red-500/10 hover:text-red-500 transition-all cursor-pointer text-left">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i> Secure Logout
                </button>
            </form>
        </nav>
    </div>
</aside>
