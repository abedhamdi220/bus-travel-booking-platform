@php
    // 💡 Dynamic user type detection for smart routing
    $isAdmin = Auth::check() && strtolower(Auth::user()->role ?? '') === 'admin';
    $isCompany = Auth::guard('company')->check();

    // 💡 Determine home and profile routes based on user role
    $homeRoute = $isCompany ? route('company.dashboard') : ($isAdmin ? route('admin.dashboard') : (Route::has('dashboard') ? route('dashboard') : '#'));
    $profileRoute = $isCompany ? route('company.profile') : ($isAdmin ? route('admin.dashboard') : (Route::has('dashboard') ? route('dashboard') : '#'));
@endphp

<nav x-data="{ open: false }" class="fixed w-full z-50 top-0 transition-all duration-300 bg-white/80 backdrop-blur-xl border-b border-white/60 shadow-[0_4px_30px_rgba(0,0,0,0.03)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo Area -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $homeRoute }}" class="flex items-center gap-3 group">
                        <div class="p-2 bg-amber-500/10 rounded-xl group-hover:bg-amber-500/20 transition-colors duration-300">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold tracking-wide text-slate-800">Sham<span class="text-amber-600">Fleets</span></span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="$homeRoute" :active="request()->url() === $homeRoute" class="text-base font-medium text-slate-700 hover:text-amber-600 transition-colors relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-amber-600 hover:after:w-full after:transition-all after:duration-300">
                        {{ __('Home') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- User Dropdown Menu -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-2 py-1.5 border border-transparent rounded-full text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-200 focus:outline-none transition ease-in-out duration-300 shadow-sm cursor-pointer group">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-100 to-amber-50 border border-amber-200 flex items-center justify-center text-amber-700 font-bold group-hover:scale-105 transition-transform">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="font-bold text-slate-700 pe-1">{{ Auth::user()->name ?? 'User' }}</div>
                            <svg class="fill-current h-4 w-4 text-slate-400 me-2 group-hover:text-amber-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden py-1">
                            <!-- Dynamic Profile Route -->
                            <x-dropdown-link href="{{ $profileRoute }}" class="block w-full px-4 py-2.5 text-start text-sm font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-700 transition-colors flex items-center gap-3">
                                <i class="fas fa-user-circle text-slate-400 text-lg"></i> <span>{{ __('Profile') }}</span>
                            </x-dropdown-link>

                            <div class="h-px bg-slate-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full px-4 py-2.5 text-start text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors flex items-center gap-3">
                                    <i class="fas fa-sign-out-alt text-red-400 text-lg"></i> <span>{{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2.5 rounded-xl text-slate-500 hover:text-amber-600 hover:bg-amber-50 focus:outline-none focus:bg-amber-50 focus:text-amber-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="sm:hidden bg-white/95 backdrop-blur-xl border-t border-slate-100 shadow-xl absolute w-full left-0 origin-top" x-cloak>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="$homeRoute" :active="request()->url() === $homeRoute" class="text-slate-700 font-semibold block px-4 py-3 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                {{ __('Home') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-3 border-t border-slate-100 bg-slate-50/50">
            <div class="px-4 mb-3 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-100 to-amber-50 border border-amber-200 flex items-center justify-center text-amber-700 font-bold">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-base text-slate-800">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email ?? '' }}</div>
                </div>
            </div>

            <div class="space-y-1 mt-3">
                <x-responsive-nav-link href="{{ $profileRoute }}" class="text-slate-700 font-semibold flex items-center gap-3 px-4 py-3 hover:bg-white transition-colors">
                    <i class="fas fa-user-circle text-slate-400 text-lg"></i> {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 font-semibold flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt text-red-400 text-lg"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
