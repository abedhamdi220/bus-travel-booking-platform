<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-8 mt-6">

     <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <!-- Main Profile Content -->
        <main class="w-full lg:w-3/4 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

                <!-- Update Profile Information -->
                <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4"><i class="fas fa-info-circle text-amber-500 mr-2"></i> Company Information</h3>

                    <form action="{{ route('company.profile.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Company Name</label>
                            <input type="text" name="name" value="{{ old('name', $information['name']) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Business Email</label>
                            <input type="email" name="email" value="{{ old('email', $information['email']) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500">
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $information['phone']) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500">
                            @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Headquarters Address</label>
                            <input type="text" name="address" value="{{ old('address', $information['address']) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Company Bio</label>
                            <textarea name="bio" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 resize-none">{{ old('bio', $information['bio']) }}</textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-8 rounded-xl shadow-md transition-colors">
                                Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="space-y-8">
                    <!-- Update Logo -->
                    <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-8">
                        <h3 class="text-xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4"><i class="fas fa-image text-amber-500 mr-2"></i> Brand Identity</h3>

                        <form action="{{ route('company.logo.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-300 rounded-2xl bg-slate-50 hover:bg-slate-100 transition relative">
                                <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
                                <p class="text-sm text-slate-500 mb-1 font-medium">Upload New Brand Logo</p>
                                <p class="text-xs text-slate-400 mb-4">PNG, JPG up to 2MB</p>

                                <input type="file" name="logo" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-2 px-6 rounded-lg shadow-sm transition-colors text-sm relative z-10 pointer-events-none">
                                    Upload Logo
                                </button>
                            </div>
                            @error('logo') <span class="text-xs text-red-500 mt-2 block text-center">{{ $message }}</span> @enderror
                        </form>
                    </div>

                    <!-- Update Password -->
                    <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-8">
                        <h3 class="text-xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4"><i class="fas fa-lock text-amber-500 mr-2"></i> Security Settings</h3>

                        <form action="{{ route('company.password.update') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Current Password</label>
                                <input type="password" name="current_password" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500">
                                @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">New Password</label>
                                <input type="password" name="new_password" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500">
                                @error('new_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500">
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-8 rounded-xl shadow-md transition-colors">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>
