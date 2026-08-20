<x-app-layout>
   <div class="flex flex-col lg:flex-row gap-8 mt-6">

   <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <main class="w-full lg:w-3/4">
            <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-8 lg:p-12">
                <div class="mb-8 border-b border-slate-100 pb-6">
                    <h1 class="text-3xl font-bold text-slate-800"><i class="fas fa-user-plus text-amber-500 mr-2"></i> Register New Driver</h1>
                    <p class="text-slate-500 mt-2">Add a new professional driver to your operational fleet.</p>
                </div>

                <form action="{{ route('company.drivers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- 1. Account Information -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-700 mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="driver@example.com" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Login Password</label>
                                <input type="password" name="password" required placeholder="Minimum 8 characters" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 2. Personal Information -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-700 mb-4">Personal Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+1234567890" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Date of Birth</label>
                                <input type="date" name="birthdate" value="{{ old('birthdate') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('birthdate') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 3. Documents Upload -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-700 mb-4">Legal Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                <label class="block text-sm font-bold text-slate-700 mb-2"><i class="fas fa-camera text-slate-400 mr-1"></i> Personal Image</label>
                                <input type="file" name="imgPersonale" accept=".jpg,.png" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 cursor-pointer">
                                @error('imgPersonale') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                <label class="block text-sm font-bold text-slate-700 mb-2"><i class="fas fa-id-card text-slate-400 mr-1"></i> National ID</label>
                                <input type="file" name="nationalPersonalImg" accept=".jpg,.png" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 cursor-pointer">
                                @error('nationalPersonalImg') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                <label class="block text-sm font-bold text-slate-700 mb-2"><i class="fas fa-car text-slate-400 mr-1"></i> Driving License</label>
                                <input type="file" name="licenseImg" accept=".jpg,.png" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 cursor-pointer">
                                @error('licenseImg') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                <label class="block text-sm font-bold text-slate-700 mb-2"><i class="fas fa-file-pdf text-slate-400 mr-1"></i> Resume / CV</label>
                                <input type="file" name="file_CV" accept=".pdf,.jpg,.png" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 cursor-pointer">
                                @error('file_CV') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end gap-4">
                        <a href="{{ route('company.mydrivers') }}" class="py-3 px-6 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">Cancel</a>
                        <button type="submit" class="py-3 px-8 bg-slate-800 hover:bg-slate-900 text-amber-400 font-bold rounded-xl shadow-md transition-colors flex items-center gap-2">
                            <i class="fas fa-save"></i> Register Driver
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>
