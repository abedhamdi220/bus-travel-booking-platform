<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Elite Transit Auth') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans text-slate-100 antialiased relative min-h-screen">
        <!-- Premium Background (Luxury Transport visual with dark overlay) -->
        <div class="absolute inset-0 z-[-1]">
            <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop" alt="Luxury Transport Background" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-[4px]"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">

            <div class="mb-8 text-center">
                <a href="/" class="flex flex-col items-center gap-4 transition-transform hover:scale-105 duration-300">
                    <div class="p-4 bg-white/10 rounded-2xl backdrop-blur-md border border-white/20">
                        <svg class="w-12 h-12 text-amber-500 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-extrabold text-white tracking-widest drop-shadow-md uppercase">Elite <span class="text-amber-500">Transit</span></h1>
                </a>
            </div>

            <!-- Glassmorphism Container Card -->
            <div class="w-full sm:max-w-md px-8 py-10 bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] overflow-hidden rounded-3xl relative">
                <!-- Subtle inner lighting -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>

                <div class="relative z-10 text-slate-100">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>