<x-app-layout>
    <!-- استخدام Alpine.js للتحكم بالتبويبات داخل لوحة المشرف -->
    <div class="flex flex-col lg:flex-row gap-8 mt-6" x-data="{ activeTab: 'overview' }">

        <!-- Sidebar: Super Admin -->
        <aside class="w-full lg:w-1/4 shrink-0">
            <div class="bg-slate-900/95 backdrop-blur-xl border border-slate-700 shadow-2xl rounded-3xl p-6 sticky top-24 text-slate-300">
                <div class="flex flex-col items-center mb-8 pb-8 border-b border-slate-700/60">
                    <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center text-slate-900 text-3xl mb-4 shadow-lg">
                        <i class="fas fa-user-shield text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ Auth::user()->name }}</h2>
                    <span class="text-xs text-red-500 mt-1 uppercase tracking-widest font-semibold"><i class="fas fa-lock"></i> Super Admin</span>
                </div>

                <nav class="space-y-2">
                    <!-- زر تبويب نظرة عامة -->
                    <button @click="activeTab = 'overview'"
                            :class="activeTab === 'overview' ? 'bg-red-500 text-white shadow-md' : 'bg-slate-800/50 hover:bg-slate-800 text-slate-300 hover:text-white'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                        <i class="fas fa-satellite-dish w-5"></i> System Overview
                    </button>

                    <!-- زر تبويب الملف الشخصي للمشرف -->
                    <button @click="activeTab = 'profile'"
                            :class="activeTab === 'profile' ? 'bg-red-500 text-white shadow-md' : 'bg-slate-800/50 hover:bg-slate-800 text-slate-300 hover:text-white'"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                        <i class="fas fa-user-cog w-5"></i> Admin Profile
                    </button>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="w-full lg:w-3/4 space-y-6">

            <div class="flex justify-between items-center mb-2">
                <h1 class="text-3xl font-bold text-slate-800" x-text="activeTab === 'overview' ? 'System Overview' : 'Admin Profile Settings'"></h1>
                <span class="text-slate-500 text-sm"><i class="far fa-calendar-alt"></i> {{ now()->format('l, F j, Y') }}</span>
            </div>

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <span class="font-medium">Please check the form for errors.</span>
                </div>
            @endif

            <!-- ============================================== -->
            <!-- التبويب الأول: System Overview -->
            <!-- ============================================== -->
            <div x-show="activeTab === 'overview'" x-transition x-cloak>

                <!-- Global Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white/70 backdrop-blur-md border border-white shadow-sm rounded-2xl p-6 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-100 rounded-full z-0 group-hover:scale-110 transition-transform"></div>
                        <div class="relative z-10">
                            <div class="text-blue-500 mb-2"><i class="fas fa-users text-2xl"></i></div>
                            <h4 class="text-slate-500 text-sm font-medium">Total Registered Users</h4>
                            <h2 class="text-3xl font-black text-slate-800 mt-1">{{ $countRegisterUser ?? 0 }}</h2>
                        </div>
                    </div>

                    <div class="bg-white/70 backdrop-blur-md border border-white shadow-sm rounded-2xl p-6 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-100 rounded-full z-0 group-hover:scale-110 transition-transform"></div>
                        <div class="relative z-10">
                            <div class="text-amber-500 mb-2"><i class="fas fa-building text-2xl"></i></div>
                            <h4 class="text-slate-500 text-sm font-medium">Total Travel Companies</h4>
                            <h2 class="text-3xl font-black text-slate-800 mt-1">{{ $countTrivelCompany ?? 0 }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Companies Management Table -->
                <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-800"><i class="fas fa-building text-red-500 mr-2"></i> Companies Network Approval</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-y border-slate-200 text-slate-500 text-sm uppercase tracking-wider">
                                    <th class="p-4 font-bold">Company Name</th>
                                    <th class="p-4 font-bold">Contact</th>
                                    <th class="p-4 font-bold">Status</th>
                                    <th class="p-4 font-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($companies ?? [] as $company)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-bold text-slate-800">{{ $company->name }}</td>
                                    <td class="p-4 text-sm text-slate-600">{{ $company->email }} <br> <span class="text-xs text-slate-400">{{ $company->phone }}</span></td>
                                    <td class="p-4">
                                        @if($company->state === 'Approved')
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Approved</span>
                                        @elseif($company->state === 'Pending')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                                        @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">{{ $company->state }}</span>
                                        @endif
                                    </td>
                                   <td class="p-4 flex justify-center gap-2">
                                        <!-- Approve -->
                                        @if($company->state !== 'Approved')
                                        <form action="{{ url('/admin/company/state/' . $company->id) }}" method="POST" onsubmit="return confirm('Approve this company?');">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="state" value="Approved">
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-xs font-bold transition">Approve</button>
                                        </form>
                                        @endif

                                        <!-- Reject (يستخدم أيضاً كإيقاف للشركة المقبولة) -->
                                        @if($company->state !== 'Rejected')
                                        <form action="{{ url('/admin/company/state/' . $company->id) }}" method="POST" onsubmit="return confirm('Reject or stop this company?');">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="state" value="Rejected">
                                            <button type="submit" class="px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-xs font-bold transition">Reject</button>
                                        </form>
                                        @endif

                                        <!-- Delete -->
                                        <form action="{{ url('/admin/company/delete/' . $company->id) }}" method="POST" onsubmit="return confirm('Permanently delete this company?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-500 text-white hover:bg-red-600 rounded-lg text-xs font-bold transition">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-500">No companies registered yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Users Management Table -->
                <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-800"><i class="fas fa-users text-blue-500 mr-2"></i> Users Management</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-y border-slate-200 text-slate-500 text-sm uppercase tracking-wider">
                                    <th class="p-4 font-bold">User Name</th>
                                    <th class="p-4 font-bold">Email</th>
                                    <th class="p-4 font-bold">Status / Role</th>
                                    <th class="p-4 font-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($users ?? [] as $user)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-bold text-slate-800">{{ $user->name }}</td>
                                    <td class="p-4 text-sm text-slate-600">{{ $user->email }}</td>
                                    <td class="p-4">
                                        @if($user->state === 'blocked')
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Blocked</span>
                                        @else
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">{{ $user->role ?? 'User' }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex justify-center gap-2">
                                        <!-- Block User -->
                                        @if($user->state !== 'blocked')
                                        <form action="{{ url('/admin/user/block/' . $user->id) }}" method="POST" onsubmit="return confirm('Block this user?');">
                                            @csrf @method('PUT')
                                            <!-- 💡 الدالة blockUser في Controller تقوم بتغيير الـ state مباشرة -->
                                            <button type="submit" class="px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-xs font-bold transition">Block</button>
                                        </form>
                                        @else
                                        <span class="px-3 py-1.5 bg-slate-100 text-slate-400 rounded-lg text-xs font-bold">Already Blocked</span>
                                        @endif

                                        <!-- Delete User -->
                                        <form action="{{ url('/admin/user/delete/' . $user->id) }}" method="POST" onsubmit="return confirm('Permanently delete this user?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-bold transition">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-500">No users registered yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- التبويب الثاني: Admin Profile (مستقل تماماً) -->
            <!-- ============================================== -->
            <div x-show="activeTab === 'profile'" x-transition x-cloak class="space-y-6">

                <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-4"><i class="fas fa-user-shield text-red-500 mr-2"></i> Update Admin Credentials</h3>

                    <!-- 💡 تأكدت هنا من أن مسار الـ action يتجه لـ route الصحيح في web.php -->
                    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Admin Name</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-red-500 focus:border-red-500">
                                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-red-500 focus:border-red-500">
                                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="my-6 border-slate-200">
                        <h4 class="text-lg font-bold text-slate-800 mb-4"><i class="fas fa-lock text-slate-500 mr-2"></i> Change Password (Optional)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Password -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Current Password</label>
                                <input type="password" name="current_password" class="w-full md:w-1/2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-red-500 focus:border-red-500" placeholder="Enter current password to authorize changes">
                                @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">New Password</label>
                                <input type="password" name="new_password" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-red-500 focus:border-red-500">
                                @error('new_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-colors">
                                Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>
