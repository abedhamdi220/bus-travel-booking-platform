<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-8 mt-6">

        <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <!-- 💡 تم إضافة وسم main لضبط المحتوى، وتعريف search الخاص بـ Alpine.js -->
        <main class="w-full lg:w-3/4 space-y-6" x-data="{ search: '' }">

            <!-- 💡 الجزء المفقود: عنوان الصفحة وزر الإضافة الذي يوجه لصفحة addTrip -->
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-3xl font-bold text-slate-800">Trips Management</h1>
                <a href="{{ route('company.addTrip') }}" class="bg-slate-800 hover:bg-slate-900 text-amber-400 font-bold py-2.5 px-6 rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i> Schedule New Trip
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6">
                <div class="mb-6 relative w-full md:w-1/2">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input x-model="search" type="text" placeholder="Search route or date..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-2.5 pl-10 pr-4 focus:ring-amber-500 text-sm">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-y border-slate-200 text-slate-500 text-sm uppercase tracking-wider">
                                <th class="p-4 font-bold">Route</th>
                                <th class="p-4 font-bold">Schedule</th>
                                <th class="p-4 font-bold">Price & Seats</th>
                                <th class="p-4 font-bold">Status</th>
                                <th class="p-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($trips as $trip)
                                <tr x-show="('{{ strtolower($trip->departure_city . ' ' . $trip->destination) }}'.includes(search.toLowerCase()) || '{{ $trip->dateTrip }}'.includes(search.toLowerCase()))" class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4">
                                        <div class="font-bold text-slate-800">{{ $trip->departure_city }} <i class="fas fa-arrow-right text-xs text-amber-500 mx-1"></i> {{ $trip->destination }}</div>
                                        <div class="text-xs text-slate-500 mt-1">Bus #{{ $trip->vehicle_id }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-sm font-medium text-slate-700">{{ $trip->dateTrip }}</div>
                                        <div class="text-xs text-slate-500"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($trip->timeTrip)->format('h:i A') }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-emerald-600">${{ $trip->cost }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $trip->totalSeats }} Seats Total</div>
                                    </td>
                                    <td class="p-4">
                                        @if($trip->dateTrip >= today()->toDateString())
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Scheduled</span>
                                        @else
                                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold">Completed</span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex justify-center">
                                        <form action="{{ route('company.trip.delete', $trip->id) }}" method="POST" onsubmit="return confirm('Cancel and delete this trip entirely?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-bold transition flex items-center gap-1">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-slate-500">No trips scheduled yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>
