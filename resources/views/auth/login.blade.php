<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white tracking-wide">Welcome Back</h2>
        <p class="text-slate-300 text-sm mt-1">Sign in to access your luxury journey</p>
    </div>

    <!-- Session or Error Messages -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 rounded-2xl text-sm flex items-center gap-2 backdrop-blur-md">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-500/40 text-red-300 rounded-2xl text-sm flex items-center gap-2 backdrop-blur-md">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ url('/login') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-200 mb-2">Email Address</label>
            <div class="relative">
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300 @error('email') border-red-500 bg-red-500/5 @enderror"
                       placeholder="example@domain.com">
            </div>
            @error('email')
                <span class="text-xs text-red-400 mt-1 block font-medium">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-slate-200 mb-2">Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password" id="password" required
                       class="w-full px-4 py-3 pr-10 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300 @error('password') border-red-500 bg-red-500/5 @enderror"
                       placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white transition cursor-pointer">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.246-3.85m3.296-3.084A9.936 9.936 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M10.582 10.582a3 3 0 104.243 4.243m-4.243-4.243L14.828 14.83"/></svg>
                </button>
            </div>
            @error('password')
                <span class="text-xs text-red-400 mt-1 block font-medium">{{ $message }}</span>
            @enderror
        </div>

        <!-- Role Selection (Added Admin) -->
        <div>
            <label for="role" class="block text-sm font-medium text-slate-200 mb-2">Account Type</label>
            <select name="role" id="role" required
                    class="w-full px-4 py-3 bg-slate-800 border border-white/10 rounded-xl text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300 cursor-pointer">
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Passenger</option>
                <option value="company" {{ old('role') == 'company' ? 'selected' : '' }}>Travel Company</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>System Administrator</option>
            </select>
            @error('role')
                <span class="text-xs text-red-400 mt-1 block font-medium">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm pt-2">
            <label class="flex items-center gap-2 text-slate-300 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="rounded bg-white/5 border-white/10 text-amber-500 focus:ring-0 focus:ring-offset-0 w-4 h-4">
                <span>Remember me</span>
            </label>
            <a href="{{ route('showForgotPasswordForm') }}" class="text-amber-400 hover:text-amber-300 transition font-medium">Forgot Password?</a>
        </div>

        <!-- Login Button -->
        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold rounded-xl shadow-lg hover:shadow-amber-500/20 transform active:scale-[0.98] transition duration-300 cursor-pointer text-base mt-2">
            Sign In
        </button>
    </form>

    <div class="text-center mt-6 pt-4 border-t border-white/10 text-sm text-slate-400">
        Don't have an account? <a href="{{ url('/register') }}" class="text-amber-400 hover:text-amber-300 transition font-medium">Create one now</a>
    </div>
</x-guest-layout>
