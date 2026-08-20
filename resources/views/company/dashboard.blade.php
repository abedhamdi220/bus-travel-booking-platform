<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-8 mt-6">

        <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <!-- Main Content (محتوى لوحة التحكم الفعلي داخل حاوية الـ flex) -->
        <main class="w-full lg:w-3/4 space-y-6">

            <div class="flex justify-between items-center mb-2">
                <h1 class="text-3xl font-bold text-slate-800">Operational Dashboard</h1>
                <span class="text-slate-500 text-sm"><i class="far fa-calendar-alt"></i> {{ now()->format('l, F j, Y') }}</span>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Today's Trips -->
                <div class="bg-white/70 backdrop-blur-md border border-white shadow-sm rounded-2xl p-6 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-100 rounded-full z-0 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-amber-500 mb-2"><i class="fas fa-bus text-2xl"></i></div>
                        <h4 class="text-slate-500 text-sm font-medium">Active Trips (Today)</h4>
                        <h2 class="text-3xl font-black text-slate-800 mt-1">{{ $information['activtripsThisday'] }}</h2>
                    </div>
                </div>

                <!-- Occupancy Rate -->
                <div class="bg-white/70 backdrop-blur-md border border-white shadow-sm rounded-2xl p-6 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-100 rounded-full z-0 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-emerald-500 mb-2"><i class="fas fa-users text-2xl"></i></div>
                        <h4 class="text-slate-500 text-sm font-medium">Occupancy Rate</h4>
                        <h2 class="text-3xl font-black text-slate-800 mt-1">{{ number_format($information['occupancyRate'] * 100, 1) }}%</h2>
                        <p class="text-xs text-slate-400 mt-1">{{ $information['countSeatsBooked'] }} / {{ $information['countSeatsAvaliable'] }} Seats</p>
                    </div>
                </div>

                <!-- Fleet Status -->
                <div class="bg-white/70 backdrop-blur-md border border-white shadow-sm rounded-2xl p-6 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-100 rounded-full z-0 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-blue-500 mb-2"><i class="fas fa-star text-2xl"></i></div>
                        <h4 class="text-slate-500 text-sm font-medium">Fleet Assets</h4>
                        <h2 class="text-3xl font-black text-slate-800 mt-1">{{ $information['countVehicle'] }}</h2>
                        <p class="text-xs text-slate-400 mt-1">Managed by {{ $information['countDriver'] }} Drivers</p>
                    </div>
                </div>

                <!-- Top Route -->
                <div class="bg-slate-800 backdrop-blur-md border border-slate-700 shadow-lg rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute right-0 bottom-0 w-32 h-32 bg-amber-500/10 rounded-tl-full z-0"></div>
                    <div class="relative z-10 text-white">
                        <div class="text-amber-400 mb-2"><i class="fas fa-map-marker-alt text-2xl"></i></div>
                        <h4 class="text-slate-400 text-sm font-medium">Top Route (Monthly)</h4>
                        @if (isset($theTOpRoute['message']) && $theTOpRoute['message'] == 'success')
                        <h2 class="text-lg font-bold mt-2 leading-tight">{{ $theTOpRoute['departure_city'] }} <br><span class="text-amber-400">→</span> {{ $theTOpRoute['destination'] }}</h2>
                        <p class="text-xs text-amber-200 mt-2">{{ $theTOpRoute['trip_count'] }} Trips completed</p>
                        @else
                        <h2 class="text-lg font-bold mt-2">No active routes</h2>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Trips -->
                <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800"><i class="far fa-calendar-alt text-amber-500 mr-2"></i> Upcoming Trips</h3>
                        <a href="{{ route('company.trips') }}" class="text-sm font-medium text-amber-600 hover:underline">View All</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($upcomingTrips as $trip)
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition border border-transparent hover:border-slate-100">
                            <div>
                                <h4 class="font-bold text-slate-700 text-sm">{{ $trip->departure_city }} → {{ $trip->destination }}</h4>
                                <p class="text-xs text-slate-500">{{ $trip->dateTrip }} at {{ $trip->timeTrip }}</p>
                            </div>
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">${{ $trip->cost }}</span>
                        </div>
                        @empty
                        <p class="text-sm text-slate-500 text-center py-4">No upcoming trips scheduled.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-ticket-alt text-amber-500 mr-2"></i> Recent Bookings</h3>
                        <a href="{{ route('company.bookings') }}" class="text-sm font-medium text-amber-600 hover:underline">View All</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentBookings as $booking)
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition border border-transparent hover:border-slate-100">
                            <div>
                                <h4 class="font-bold text-slate-700 text-sm">{{ $booking->passenger_name }}</h4>
                                <p class="text-xs text-slate-500">{{ $booking->departure_city }} → {{ $booking->destination }}</p>
                            </div>
                            <span class="text-sm font-black text-emerald-600">${{ $booking->cost }}</span>
                        </div>
                        @empty
                        <p class="text-sm text-slate-500 text-center py-4">No recent bookings found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div> <!-- تم الإغلاق الصحيح لحاوية الـ flex هنا في نهاية المحتوى -->
</x-app-layout>
