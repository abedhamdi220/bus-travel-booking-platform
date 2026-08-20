<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-8 mt-6">

     <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <!-- Main Content -->
        <main class="w-full lg:w-3/4 space-y-6" x-data="{ filter: 'all', search: '' }">

            <div class="flex justify-between items-center mb-2">
                <h1 class="text-3xl font-bold text-slate-800">Fleet Assets</h1>
                <a href="{{ route('company.add-bus') }}" class="bg-slate-800 hover:bg-slate-900 text-amber-400 font-bold py-2.5 px-6 rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add New Bus
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white/70 backdrop-blur-md border border-white shadow-sm rounded-2xl p-6 relative overflow-hidden">
                    <div class="text-amber-500 mb-2"><i class="fas fa-bus-alt text-2xl"></i></div>
                    <h4 class="text-slate-500 text-sm font-medium">Total Fleet</h4>
                    <h2 class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</h2>
                </div>
                <div class="bg-red-50/70 backdrop-blur-md border border-red-100 shadow-sm rounded-2xl p-6 relative overflow-hidden">
                    <div class="text-red-500 mb-2"><i class="fas fa-tools text-2xl"></i></div>
                    <h4 class="text-red-700 text-sm font-medium">In Maintenance</h4>
                    <h2 class="text-3xl font-black text-red-800 mt-1">{{ $stats['maintenance'] }}</h2>
                </div>
            </div>

            <!-- Toolbar & Filter -->
            <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <div class="flex gap-2 bg-slate-100 p-1 rounded-xl w-full md:w-auto">
                        <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-white shadow-sm text-amber-600' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all w-full md:w-auto">All</button>
                        <button @click="filter = 'availability'" :class="filter === 'availability' ? 'bg-white shadow-sm text-amber-600' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all w-full md:w-auto">Available</button>
                        <button @click="filter = 'maintenance'" :class="filter === 'maintenance' ? 'bg-white shadow-sm text-amber-600' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all w-full md:w-auto">Maintenance</button>
                    </div>
                    <div class="w-full md:w-72 relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input x-model="search" type="text" placeholder="Search plate or number..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 pl-10 pr-4 focus:ring-amber-500 text-sm">
                    </div>
                </div>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-y border-slate-200 text-slate-500 text-sm uppercase tracking-wider">
                                <th class="p-4 font-bold">Bus ID</th>
                                <th class="p-4 font-bold">Plate Number</th>
                                <th class="p-4 font-bold">Type</th>
                                <th class="p-4 font-bold">Status</th>
                                <th class="p-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($vehicles as $vehicle)
                                <tr x-show="(filter === 'all' || filter === '{{ $vehicle->state }}') && ('{{ strtolower($vehicle->plate_number) }}'.includes(search.toLowerCase()) || 'bus {{ $vehicle->vehicle_number }}'.includes(search.toLowerCase()))"
                                    class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4">
                                        <div class="font-bold text-slate-800">Bus #{{ $vehicle->vehicle_number }}</div>
                                    </td>
                                    <td class="p-4 font-medium text-slate-600">{{ $vehicle->plate_number }}</td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold">{{ $vehicle->type_vehicle === 'nurmal' ? 'Regular' : 'VIP' }}</span>
                                    </td>
                                    <td class="p-4">
                                        @if($vehicle->state === 'availability')
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold"><i class="fas fa-check-circle mr-1"></i> Available</span>
                                        @elseif($vehicle->state === 'maintenance')
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold"><i class="fas fa-tools mr-1"></i> Maintenance</span>
                                        @elseif($vehicle->state === 'on_trip')
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold"><i class="fas fa-road mr-1"></i> On Trip</span>
                                        @else
                                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold">{{ ucfirst($vehicle->state) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex justify-center gap-3">
                                        <a href="{{ url('company/bus-detailes', $vehicle->id) }}" class="text-blue-500 hover:text-blue-700 transition" title="View Details">
                                            <i class="fas fa-eye text-lg"></i>
                                        </a>
                                        <form action="{{ route('company.buses.delete', $vehicle->id) }}" method="POST" onsubmit="return confirm('Delete this vehicle permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Delete Bus">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500">No vehicles found in your fleet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>
