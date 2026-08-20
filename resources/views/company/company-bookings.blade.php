<x-app-layout>
<div class="flex flex-col lg:flex-row gap-8 mt-6">

   <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <main class="w-full lg:w-3/4 space-y-6" x-data="{ search: '', filter: 'all' }">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-3xl font-bold text-slate-800">Booking Records</h1>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <div class="flex gap-2 bg-slate-100 p-1 rounded-xl w-full md:w-auto">
                        <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-white shadow-sm text-amber-600' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all">All</button>
                        <button @click="filter = 'upcoming'" :class="filter === 'upcoming' ? 'bg-white shadow-sm text-amber-600' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all">Upcoming</button>
                        <button @click="filter = 'past'" :class="filter === 'past' ? 'bg-white shadow-sm text-amber-600' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all">Completed</button>
                    </div>
                    <div class="w-full md:w-72 relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input x-model="search" type="text" placeholder="Search passenger or booking ID..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 pl-10 pr-4 focus:ring-amber-500 text-sm">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-y border-slate-200 text-slate-500 text-sm uppercase tracking-wider">
                                <th class="p-4 font-bold">Booking ID</th>
                                <th class="p-4 font-bold">Passenger & Route</th>
                                <th class="p-4 font-bold">Trip Date</th>
                                <th class="p-4 font-bold">Status</th>
                                <th class="p-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $bk)
                                @php $isUpcoming = $bk->dateTrip >= today()->toDateString(); @endphp
                                <tr x-show="(filter === 'all' || (filter === 'upcoming' && '{{ $isUpcoming }}' == '1') || (filter === 'past' && '{{ $isUpcoming }}' == '')) && ('{{ strtolower($bk->passenger_name . ' bk-' . $bk->booking_id) }}'.includes(search.toLowerCase()))" class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-mono font-bold text-amber-600">#BK-{{ $bk->booking_id }}</td>
                                    <td class="p-4">
                                        <div class="font-bold text-slate-800">{{ $bk->passenger_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $bk->departure_city }} → {{ $bk->destination }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-sm font-medium text-slate-700">{{ $bk->dateTrip }}</div>
                                        <div class="text-xs text-slate-500">Seat #{{ $bk->seat_id }}</div>
                                    </td>
                                    <td class="p-4">
                                        @if($isUpcoming)
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Upcoming</span>
                                        @else
                                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold">Completed</span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex justify-center">
                                        <form action="{{ route('company.bookings.cancel', $bk->booking_id) }}" method="POST" onsubmit="return confirm('Cancel this booking and release the seat?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-bold transition flex items-center gap-1">
                                                <i class="fas fa-times-circle"></i> Cancel
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-slate-500">No bookings found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
