<x-app-layout>
    <!-- Alpine.js application for dropdowns and active tabs control -->
    <div x-data="{ activeTab: 'bookings' }" class="max-w-7xl mx-auto mt-6">

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Sidebar - Z-Layout Left Side (Standard LTR) -->
            <aside class="w-full lg:w-1/4 shrink-0">
                <div class="bg-white/60 backdrop-blur-xl border border-white/60 shadow-[0_4px_20px_rgba(0,0,0,0.03)] rounded-3xl p-6 sticky top-24">

                    <!-- User Profile & Avatar -->
                    <div class="flex flex-col items-center mb-8 pb-8 border-b border-slate-200/60">
                        <div class="relative w-24 h-24 rounded-full bg-gradient-to-tr from-amber-400 to-amber-600 p-1 mb-4 shadow-lg">
                            <div class="w-full h-full bg-white rounded-full flex items-center justify-center overflow-hidden">
                                <!-- Fallback to first letter if no image -->
                                <span class="text-3xl font-bold text-amber-600">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                        <span class="text-sm text-slate-500">{{ $user->email }}</span>
                        <span class="mt-2 inline-block px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full tracking-wide">VIP Passenger</span>
                    </div>

                    <!-- Navigation Tabs -->
                    <nav class="space-y-2">
                        <button @click="activeTab = 'bookings'"
                                :class="activeTab === 'bookings' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:bg-white/50'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-300">
                            <i class="fas fa-ticket-alt w-5"></i> My Bookings
                        </button>

                        <button @click="activeTab = 'profile'"
                                :class="activeTab === 'profile' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:bg-white/50'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-300">
                            <i class="fas fa-user-edit w-5"></i> Account Details
                        </button>

                        <button @click="activeTab = 'security'"
                                :class="activeTab === 'security' ? 'bg-slate-800 text-white shadow-md' : 'text-slate-600 hover:bg-white/50'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-300">
                            <i class="fas fa-shield-alt w-5"></i> Security & Access
                        </button>
                    </nav>

                </div>
            </aside>

            <!-- Main Content - Z-Layout Right Side (Standard LTR) -->
            <main class="w-full lg:w-3/4">

                <!-- Success / Error Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3 shadow-sm">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                        <span class="font-medium">Please correct the errors in the form below.</span>
                    </div>
                @endif

                <!-- Tab: My Bookings -->
                <div x-show="activeTab === 'bookings'" x-transition.opacity.duration.400ms class="space-y-6" x-cloak>
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 shadow-sm rounded-3xl p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-slate-800">Trip History</h3>
                            <a href="{{ route('landing') }}" class="text-amber-600 font-medium hover:underline text-sm transition-colors">Book a New Trip</a>
                        </div>

                        <div class="space-y-4">
                            @forelse($trips as $trip)
                                <div class="p-5 border border-slate-200 rounded-2xl bg-white/50 hover:bg-white transition-colors flex flex-col md:flex-row justify-between items-center gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fas fa-bus-alt text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $trip->departure_city }} <i class="fas fa-arrow-right text-xs text-slate-400 mx-1"></i> {{ $trip->destination }}</h4>
                                            <span class="text-sm text-slate-500"><i class="far fa-calendar-alt mr-1"></i> {{ $trip->dateTrip }} &nbsp;|&nbsp; <i class="far fa-clock mr-1"></i> {{ $trip->timeTrip }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 w-full md:w-auto">
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold w-full md:w-auto text-center tracking-wide">CONFIRMED</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                                        <i class="fas fa-ticket-alt text-2xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-700">No previous bookings found</h4>
                                    <p class="text-slate-500 text-sm mt-1">Start exploring our premium destinations today.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab: Account Details -->
                <div x-show="activeTab === 'profile'" x-transition.opacity.duration.400ms class="space-y-6" x-cloak>
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 shadow-sm rounded-3xl p-8">
                        <h3 class="text-2xl font-bold text-slate-800 mb-6">Update Personal Information</h3>

                        <form action="{{ route('profile.update', $profile->id ?? 0) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <!-- Profile Picture Upload -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Profile Picture</label>
                                <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-slate-200 rounded-xl p-2 bg-white cursor-pointer transition-all">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $profile->name ?? $user->name) }}" class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 transition-shadow">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 transition-shadow">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Address</label>
                                    <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 transition-shadow">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Short Bio</label>
                                    <textarea name="bio" rows="3" class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 resize-none transition-shadow">{{ old('bio', $profile->bio ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="pt-4 text-right">
                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-colors">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tab: Security (Password & Account Deletion) -->
                <div x-show="activeTab === 'security'" x-transition.opacity.duration.400ms class="space-y-6" x-cloak>

                    <!-- Change Password Section -->
                    <div class="bg-white/60 backdrop-blur-xl border border-white/60 shadow-sm rounded-3xl p-8">
                        <h3 class="text-2xl font-bold text-slate-800 mb-6"><i class="fas fa-lock text-amber-500 mr-2"></i> Change Password</h3>

                        <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Current Password</label>
                                <input type="password" name="current_password" required class="w-full lg:w-1/2 bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 transition-shadow">
                                @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">New Password</label>
                                <input type="password" name="new_password" required class="w-full lg:w-1/2 bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 transition-shadow">
                                @error('new_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" required class="w-full lg:w-1/2 bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 transition-shadow">
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-colors">
                                    Update Security
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Danger Zone (Delete Account) -->
                    <div class="bg-red-50/50 backdrop-blur-xl border border-red-100 rounded-3xl p-8">
                        <h3 class="text-xl font-bold text-red-600 mb-2">Danger Zone</h3>
                        <p class="text-slate-600 text-sm mb-6">Once your account is deleted, all of its resources, data, and bookings will be permanently deleted and cannot be recovered.</p>

                        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to permanently delete your account?');">
                            @csrf
                            @method('DELETE')

                            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
                                <div class="w-full lg:w-1/2">
                                    <label class="block text-sm font-medium text-red-500 mb-1.5">Please enter your password to confirm</label>
                                    <input type="password" name="password" required class="w-full bg-white border border-red-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-red-500 focus:border-red-500 transition-shadow">
                                    @error('password', 'userDeletion') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="shrink-0 w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-colors">
                                    Delete Account
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>