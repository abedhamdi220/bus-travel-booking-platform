<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Elite Transit') }}</title>

        <!-- Premium English Font: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Vite CSS/JS integration -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            /* Custom scrollbar optimized for premium feel */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

            /* Prevent horizontal scroll jitter */
            html { overflow-x: hidden; }
        </style>
    </head>
    <body class="antialiased text-slate-800 relative bg-slate-50">

        <!-- Elegant subtle gradient background -->
        <div class="fixed inset-0 z-[-1] bg-gradient-to-br from-slate-100 via-gray-50 to-slate-200"></div>

        <!-- Professional layout spacing: pt-28 ensures 112px clearance for 80px fixed navbar -->
        <div class="min-h-screen flex flex-col pt-28">

            @include('layouts.navigation')

            @isset($header)
                <header class="mx-4 sm:mx-6 lg:mx-8 bg-white/40 backdrop-blur-md border border-white/60 shadow-sm rounded-2xl mb-6">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Main View Slot -->
            <main class="flex-grow max-w-7xl w-full mx-auto pb-8 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

        </div>
    </body>
</html>