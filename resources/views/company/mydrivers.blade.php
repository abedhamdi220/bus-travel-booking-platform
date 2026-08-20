<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-8 mt-6">
<!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <!-- Main Content -->
        <main class="w-full lg:w-3/4 space-y-6" x-data="{ search: '' }">

            <div class="flex justify-between items-center mb-2">
                <h1 class="text-3xl font-bold text-slate-800">Drivers Management</h1>
                <a href="{{ route('company.drivers.create') }}" class="bg-slate-800 hover:bg-slate-900 text-amber-400 font-bold py-2.5 px-6 rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Add New Driver
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/70 backdrop-blur-md border border-white shadow-sm rounded-2xl p-6">
                    <div class="text-slate-400 mb-2"><i class="fas fa-users text-2xl"></i></div>
                    <h4 class="text-slate-500 text-sm font-medium">Total Drivers</h4>
                    <h2 class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</h2>
                </div>
                <div class="bg-emerald-50/70 backdrop-blur-md border border-emerald-100 shadow-sm rounded-2xl p-6">
                    <div class="text-emerald-500 mb-2"><i class="fas fa-check-circle text-2xl"></i></div>
                    <h4 class="text-emerald-700 text-sm font-medium">Active & Approved</h4>
                    <h2 class="text-3xl font-black text-emerald-800 mt-1">{{ $stats['approved'] }}</h2>
                </div>
                <div class="bg-amber-50/70 backdrop-blur-md border border-amber-100 shadow-sm rounded-2xl p-6">
                    <div class="text-amber-500 mb-2"><i class="fas fa-clock text-2xl"></i></div>
                    <h4 class="text-amber-700 text-sm font-medium">Pending / Suspended</h4>
                    <h2 class="text-3xl font-black text-amber-800 mt-1">{{ $stats['pending'] }}</h2>
                </div>
            </div>

            <!-- Data Table Container -->
            <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6">
                <div class="mb-6 relative w-full md:w-1/2">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input x-model="search" type="text" placeholder="Search driver by name or email..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 pl-10 pr-4 focus:ring-amber-500 text-sm">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-y border-slate-200 text-slate-500 text-sm uppercase tracking-wider">
                                <th class="p-4 font-bold">Driver Info</th>
                                <th class="p-4 font-bold">Contact</th>
                                <th class="p-4 font-bold">Availability</th>
                                <th class="p-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($drivers as $driver)
                                <tr x-show="('{{ strtolower($driver->user->name ?? '') }}'.includes(search.toLowerCase()) || '{{ strtolower($driver->user->email ?? '') }}'.includes(search.toLowerCase()))" class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold">
                                                {{ strtoupper(substr($driver->user->name ?? 'D', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800">{{ $driver->user->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-slate-500">Joined: {{ $driver->created_at->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-sm text-slate-700 font-medium"><i class="fas fa-phone text-slate-400 mr-1"></i> {{ $driver->phone }}</div>
                                        <div class="text-xs text-slate-500 mt-1"><i class="fas fa-envelope text-slate-400 mr-1"></i> {{ $driver->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-4">
                                        @if($driver->availability_state === 'availability')
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Available</span>
                                        @else
                                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ ucfirst($driver->availability_state) }}</span>
                                        @endif
                                        <div class="mt-1">
                                            <span class="text-xs font-semibold {{ $driver->state === 'Approved' ? 'text-blue-500' : 'text-red-500' }}">[{{ $driver->state }}]</span>
                                        </div>
                                    </td>
                                    <td class="p-4 flex justify-center gap-3">
                                        <!-- Form للـ Delete بدلاً من JS fetch -->
                                        <form action="{{ route('company.drivers.delete', $driver->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to dismiss this driver?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-bold transition flex items-center gap-1">
                                                <i class="fas fa-user-times"></i> Dismiss
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-500">No drivers assigned to your company yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
