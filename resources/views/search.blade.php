<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4">
            <div>
                <span class="text-amber-500 font-bold tracking-widest uppercase text-sm mb-2 block">Select Your Journey</span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900">Search Results</h1>
                <p class="text-slate-500 mt-2">
                    We found <span class="font-bold text-amber-600 px-1">{{ $trips->count() }}</span> journeys matching your criteria.
                </p>
            </div>

            <a href="{{ route('landing') }}" class="text-sm font-medium text-slate-500 hover:text-amber-600 transition-colors flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-200">
                <i class="fas fa-search"></i> Modify Search
            </a>
        </div>

        <!-- Results List -->
        <div class="space-y-6">
            @forelse ($trips as $trip)
                @php
                    // التحقق مما إذا كانت الرحلة قد انتهت (تاريخها أقدم من اليوم)
                    $isExpired = \Carbon\Carbon::parse($trip->dateTrip)->isBefore(now()->startOfDay());
                @endphp

                <!-- Premium Ticket Card (Dynamic styling based on isExpired) -->
                <div class="bg-white rounded-3xl border {{ $isExpired ? 'border-red-200 opacity-75 grayscale-[20%]' : 'border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_40px_rgba(0,0,0,0.08)]' }} transition-all duration-300 flex flex-col md:flex-row items-stretch relative overflow-hidden group">

                    <!-- Decorative Left Accent -->
                    @if(!$isExpired)
                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-gradient-to-b from-amber-400 to-amber-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    @endif

                    <!-- Expired Badge -->
                    @if($isExpired)
                        <div class="absolute top-4 left-4 z-20">
                            <span class="bg-red-100 text-red-600 border border-red-200 text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1">
                                <i class="fas fa-history"></i> Trip Ended
                            </span>
                        </div>
                    @endif

                    <!-- Main Route Info -->
                    <div class="flex-1 p-6 md:p-8 flex flex-col justify-center {{ $isExpired ? 'pt-12' : '' }}">
                        <!-- Fleet Info -->
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 {{ $isExpired ? 'bg-slate-300 text-slate-500' : 'bg-slate-900 text-amber-400' }} rounded-xl flex items-center justify-center shadow-inner">
                                <i class="fas fa-star text-sm"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Operated By</span>
                                <span class="font-bold {{ $isExpired ? 'text-slate-500' : 'text-slate-800' }}">{{ $trip->company->name ?? 'Premium Fleet' }}</span>
                                <span class="text-xs text-slate-500 block mt-1">
                                    <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($trip->dateTrip)->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Route Visualizer -->
                        <div class="flex flex-row items-center justify-between gap-4 w-full">
                            <!-- Departure -->
                            <div class="text-left w-1/3">
                                <span class="text-3xl font-black {{ $isExpired ? 'text-slate-400' : 'text-slate-900' }} block tracking-tighter">{{ \Carbon\Carbon::parse($trip->timeTrip)->format('h:i A') }}</span>
                                <span class="text-sm font-bold text-slate-500 mt-1 block">{{ $trip->departure_city }}</span>
                            </div>

                            <!-- Animated Bus Divider -->
                            <div class="flex-1 flex flex-col items-center justify-center relative px-2">
                                <div class="text-xs {{ $isExpired ? 'text-slate-400 bg-slate-100' : 'text-slate-400 bg-slate-50' }} mb-2 font-medium px-3 py-1 rounded-full border border-slate-100">Direct</div>
                                <div class="w-full relative flex items-center justify-center">
                                    <div class="h-[2px] border-t-2 border-dashed border-slate-200 w-full absolute z-0"></div>
                                    <!-- Bus icon (no animation if expired) -->
                                    <i class="fas fa-bus {{ $isExpired ? 'text-slate-300' : 'text-amber-500 group-hover:translate-x-6 transition-transform duration-700 ease-in-out' }} text-xl z-10 bg-white px-4 relative"></i>
                                </div>
                            </div>

                            <!-- Destination -->
                            <div class="text-right w-1/3">
                                <span class="text-3xl font-black text-slate-400 block tracking-tighter">--:--</span>
                                <span class="text-sm font-bold text-slate-500 mt-1 block">{{ $trip->destination }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Vertical Ticket Divider -->
                    <div class="hidden md:flex flex-col justify-between items-center relative w-px">
                        <div class="w-4 h-4 rounded-full {{ $isExpired ? 'bg-slate-100' : 'bg-slate-50' }} absolute -top-2 -left-2 border border-slate-100"></div>
                        <div class="h-full border-l-2 border-dashed border-slate-200"></div>
                        <div class="w-4 h-4 rounded-full {{ $isExpired ? 'bg-slate-100' : 'bg-slate-50' }} absolute -bottom-2 -left-2 border border-slate-100"></div>
                    </div>
                    <div class="md:hidden flex flex-row justify-between items-center relative h-px w-full">
                        <div class="w-4 h-4 rounded-full {{ $isExpired ? 'bg-slate-100' : 'bg-slate-50' }} absolute -left-2 -top-2 border border-slate-100"></div>
                        <div class="w-full border-t-2 border-dashed border-slate-200"></div>
                        <div class="w-4 h-4 rounded-full {{ $isExpired ? 'bg-slate-100' : 'bg-slate-50' }} absolute -right-2 -top-2 border border-slate-100"></div>
                    </div>

                    <!-- Price & CTA Section -->
                    <div class="p-6 md:p-8 {{ $isExpired ? 'bg-slate-100/50' : 'bg-slate-50/50' }} flex flex-col items-center justify-center min-w-[240px]">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Ticket Price</span>
                        <div class="text-3xl font-black {{ $isExpired ? 'text-slate-400' : 'text-amber-600' }} mb-6 flex items-baseline gap-1">
                            {{ $trip->cost ?? '0' }} <span class="text-base font-bold text-slate-400">SYP</span>
                        </div>

                        @if($isExpired)
                            <button disabled class="w-full bg-slate-300 text-slate-500 font-bold py-3 px-6 rounded-xl cursor-not-allowed text-center flex items-center justify-center gap-2">
                                <i class="fas fa-lock"></i> Unavailable
                            </button>
                        @else
                            <a href="{{ route('booking.seats', $trip->id) }}" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center flex items-center justify-center gap-2 group/btn">
                                Select Seats
                                <i class="fas fa-arrow-right text-amber-400 group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        @endif

                        <!-- Amenity Trust Signals -->
                        <div class="flex gap-3 mt-4 text-slate-400 text-xs {{ $isExpired ? 'opacity-50' : '' }}">
                            <i class="fas fa-wifi" title="Free WiFi"></i>
                            <i class="fas fa-snowflake" title="Air Conditioning"></i>
                            <i class="fas fa-couch" title="VIP Seats"></i>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Premium Empty State -->
                <div class="text-center py-24 bg-white rounded-[2.5rem] border border-dashed border-slate-300 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 border border-slate-100 shadow-inner">
                        <i class="fas fa-route text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-800 mb-3">No Journeys Found</h3>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">
                        We couldn't find any fleets matching your exact criteria. Try adjusting your dates or locations.
                    </p>
                    <a href="{{ route('landing') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold py-3 px-8 rounded-xl shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-all flex items-center gap-2">
                        <i class="fas fa-sliders-h"></i> Modify Search
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
