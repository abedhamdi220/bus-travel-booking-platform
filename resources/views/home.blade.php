<x-app-layout>
    <!-- 1. HERO & BOOKING ENGINE SECTION -->
    <div class="relative rounded-[2.5rem] overflow-hidden mb-20 shadow-2xl border border-slate-200/50">
        <!-- Background Image with Parallax-like feel -->
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-[20s]" alt="Luxury Bus Travel">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-slate-900/40 backdrop-blur-[2px]"></div>
        </div>

        <div class="relative z-10 px-6 py-24 lg:px-12 flex flex-col items-start text-left w-full max-w-7xl mx-auto">
            <span class="px-4 py-1.5 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 text-sm font-bold tracking-wider mb-6 backdrop-blur-md uppercase">
                Premium Road Travel
            </span>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6 drop-shadow-2xl tracking-tight leading-tight max-w-3xl">
                Redefining your <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-600">Journey.</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 mb-12 max-w-2xl font-light">
                Experience first-class road travel. Search, compare, and book your seats in the most luxurious bus fleets with absolute ease.
            </p>

            <!-- Booking Engine (Alpine.js powered) -->
            <div x-data="{ tripType: 'one_way', passengers: 1 }" class="w-full xl:max-w-6xl bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl p-6 md:p-8 shadow-[0_8px_40px_0_rgba(0,0,0,0.5)]">

                <!-- Trip Type Tabs -->
                <div class="flex gap-8 mb-8 border-b border-white/10 pb-4">
                    <label class="cursor-pointer flex items-center gap-2 text-white font-medium group">
                        <input type="radio" x-model="tripType" value="one_way" class="w-5 h-5 text-amber-500 focus:ring-amber-500 bg-slate-800/50 border-white/30 transition-all">
                        <span class="group-hover:text-amber-400 transition-colors">One Way</span>
                    </label>
                    <label class="cursor-pointer flex items-center gap-2 text-white font-medium group">
                        <input type="radio" x-model="tripType" value="round_trip" class="w-5 h-5 text-amber-500 focus:ring-amber-500 bg-slate-800/50 border-white/30 transition-all">
                        <span class="group-hover:text-amber-400 transition-colors">Round Trip</span>
                    </label>
                </div>

                <form action="{{ route('search.trips') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 items-end">

                        <!-- Departure -->
                        <div class="relative group">
                            <label class="block text-xs font-bold text-amber-400 mb-2 uppercase tracking-widest">Leaving From</label>
                            <div class="relative">
                                <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-hover:text-amber-400 transition-colors"></i>
                                <select name="departure_city" required class="w-full bg-slate-800/80 border border-white/10 text-white rounded-2xl py-4 pl-12 pr-4 focus:ring-2 focus:ring-amber-500 focus:border-transparent appearance-none cursor-pointer transition-all hover:bg-slate-800">
                                    <option value="" disabled selected>Select City</option>
                                    @foreach ($departureCities as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Destination -->
                        <div class="relative group">
                            <label class="block text-xs font-bold text-amber-400 mb-2 uppercase tracking-widest">Going To</label>
                            <div class="relative">
                                <i class="fas fa-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-hover:text-amber-400 transition-colors"></i>
                                <select name="destination" required class="w-full bg-slate-800/80 border border-white/10 text-white rounded-2xl py-4 pl-12 pr-4 focus:ring-2 focus:ring-amber-500 focus:border-transparent appearance-none cursor-pointer transition-all hover:bg-slate-800">
                                    <option value="" disabled selected>Select Destination</option>
                                    @foreach ($destinationCities as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Departure Date -->
                        <div class="relative group">
                            <label class="block text-xs font-bold text-amber-400 mb-2 uppercase tracking-widest">Departure Date</label>
                            <div class="relative">
                                <input type="date" name="dateTrip" required class="w-full bg-slate-800/80 border border-white/10 text-white rounded-2xl py-4 px-4 focus:ring-2 focus:ring-amber-500 focus:border-transparent cursor-pointer [color-scheme:dark] transition-all hover:bg-slate-800">
                            </div>
                        </div>

                        <!-- Passengers -->
                        <div class="relative">
                            <label class="block text-xs font-bold text-amber-400 mb-2 uppercase tracking-widest">Passengers</label>
                            <div class="flex items-center justify-between bg-slate-800/80 border border-white/10 rounded-2xl p-1.5 h-[58px]">
                                <button type="button" @click="if(passengers > 1) passengers--" class="w-10 h-10 bg-slate-700/50 hover:bg-amber-500/20 text-slate-300 hover:text-amber-400 rounded-xl flex items-center justify-center transition-colors">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <input type="number" name="passengers" x-model="passengers" readonly class="w-12 bg-transparent border-0 text-center text-white font-bold text-lg focus:ring-0 p-0 pointer-events-none">
                                <button type="button" @click="if(passengers < 10) passengers++" class="w-10 h-10 bg-slate-700/50 hover:bg-amber-500/20 text-slate-300 hover:text-amber-400 rounded-xl flex items-center justify-center transition-colors">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Extra Options (Return Date & Fleet Preference) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-5">

                        <div class="relative group lg:col-start-3" x-show="tripType === 'round_trip'" style="display: none;">
                            <label class="block text-xs font-bold text-amber-400 mb-2 uppercase tracking-widest">Return Date</label>
                            <input type="date" name="returnDate" class="w-full bg-slate-800/80 border border-white/10 text-white rounded-2xl py-4 px-4 focus:ring-2 focus:ring-amber-500 focus:border-transparent cursor-pointer [color-scheme:dark] transition-all hover:bg-slate-800">
                        </div>

                        <div class="relative group" :class="tripType === 'one_way' ? 'lg:col-start-4' : 'lg:col-start-4'">
                            <label class="block text-xs font-bold text-amber-400 mb-2 uppercase tracking-widest">Preferred Fleet (Optional)</label>
                            <div class="relative">
                                <i class="fas fa-bus absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-hover:text-amber-400 transition-colors"></i>
                                <select name="company_id" class="w-full bg-slate-800/80 border border-white/10 text-slate-300 rounded-2xl py-4 pl-12 pr-4 focus:ring-2 focus:ring-amber-500 focus:border-transparent appearance-none cursor-pointer transition-all hover:bg-slate-800">
                                    <option value="">All Fleets</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                  <!-- Submit Button -->
                    <div class="mt-8 pt-8 border-t border-white/10 flex flex-col-reverse md:flex-row justify-end items-center gap-4">

                        <!-- الزر الجديد: يعرض كافة الرحلات بتخطي فلتر البحث -->
                        <a href="{{ route('search.trips') }}" class="w-full md:w-auto bg-slate-800/80 hover:bg-slate-700 text-white font-bold text-lg py-4 px-8 rounded-2xl border border-white/10 transition-all duration-300 flex justify-center items-center">
                            Explore All Available Trips
                        </a>

                        <!-- زر البحث الأساسي -->
                        <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-900 font-extrabold text-lg py-4 px-10 rounded-2xl shadow-[0_0_30px_rgba(245,158,11,0.3)] hover:shadow-[0_0_40px_rgba(245,158,11,0.5)] transform hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-3">
                            Search Trips <i class="fas fa-arrow-right text-xl"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. HOW IT WORKS SECTION (End-User Clarity) -->
    <div class="mb-24 mt-12 px-4">
        <div class="text-center mb-16">
            <span class="text-amber-600 font-bold tracking-widest uppercase text-sm">Simple Process</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mt-2">How It Works</h2>
            <p class="text-slate-500 mt-4 max-w-2xl mx-auto">Booking your luxury journey has never been easier. Get your ticket in three simple steps.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <!-- Decorative connection line (Desktop only) -->
            <div class="hidden md:block absolute top-12 left-1/6 right-1/6 h-0.5 bg-slate-200 -z-10"></div>

            <div class="bg-white rounded-3xl p-8 text-center border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-2 transition-transform duration-300">
                <div class="w-20 h-20 mx-auto bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm border border-amber-100">
                    <i class="fas fa-search-location"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">1. Find Your Route</h3>
                <p class="text-slate-500 leading-relaxed">Enter your departure, destination, and travel dates to browse available VIP fleets.</p>
            </div>

            <div class="bg-white rounded-3xl p-8 text-center border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-2 transition-transform duration-300">
                <div class="w-20 h-20 mx-auto bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm border border-amber-100">
                    <i class="fas fa-chair"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">2. Select Your Seat</h3>
                <p class="text-slate-500 leading-relaxed">Choose your preferred seat from our interactive bus map for maximum comfort.</p>
            </div>

            <div class="bg-white rounded-3xl p-8 text-center border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-2 transition-transform duration-300">
                <div class="w-20 h-20 mx-auto bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm border border-amber-100">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">3. Book & Travel</h3>
                <p class="text-slate-500 leading-relaxed">Securely pay online and receive your digital ticket instantly. Just show up and relax.</p>
            </div>
        </div>
    </div>

    <!-- 3. ABOUT US SECTION (Brand Story) -->
    <div class="mb-24 bg-slate-900 rounded-[2.5rem] overflow-hidden flex flex-col md:flex-row items-stretch shadow-2xl border border-slate-800">
        <div class="w-full md:w-1/2 p-12 lg:p-16 flex flex-col justify-center relative">
            <!-- Subtle decorative background element -->
            <div class="absolute top-0 left-0 w-32 h-32 bg-amber-500/10 rounded-br-full blur-2xl"></div>

            <span class="text-amber-500 font-bold tracking-widest uppercase text-sm mb-4">Who We Are</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Elevating the Standard of Road Travel.</h2>
            <p class="text-slate-300 mb-6 leading-relaxed text-lg font-light">
                We believe that the journey should be as magnificent as the destination. Our platform brings together the elite bus fleets of the region into one seamless booking experience.
            </p>
            <p class="text-slate-400 mb-8 leading-relaxed">
                Whether you are traveling for business or leisure, we guarantee state-of-the-art vehicles, highly trained professional drivers, and a commitment to punctuality and safety that is unmatched in the industry.
            </p>
            <div class="flex items-center gap-6">
                <div class="flex flex-col">
                    <span class="text-3xl font-extrabold text-amber-500">50+</span>
                    <span class="text-xs text-slate-400 uppercase tracking-wider mt-1">Premium Fleets</span>
                </div>
                <div class="w-px h-10 bg-slate-700"></div>
                <div class="flex flex-col">
                    <span class="text-3xl font-extrabold text-amber-500">10k+</span>
                    <span class="text-xs text-slate-400 uppercase tracking-wider mt-1">Happy Travelers</span>
                </div>
            </div>
        </div>
        <div class="w-full md:w-1/2 relative min-h-[400px]">
            <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover" alt="Luxury Bus Interior">
            <div class="absolute inset-0 bg-gradient-to-l from-transparent to-slate-900"></div>
        </div>
    </div>

    <!-- 4. EXCLUSIVE OFFERS (Ads Section Retained) -->
    @if($ads->isNotEmpty())
    <div class="mb-24">
        <div class="flex justify-between items-end mb-8 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800">Exclusive Offers</h2>
                <p class="text-slate-500 mt-2">Special deals from our premium partners.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($ads as $ad)
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-lg transition-all relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -z-10 transition-transform group-hover:scale-125"></div>
                    <div class="flex items-start gap-5">
                        <div class="w-14 h-14 bg-amber-500/10 text-amber-600 rounded-2xl flex items-center justify-center shrink-0">
                            <i class="fas fa-tag text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 mb-2 text-lg">Special Promotion</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">{{ $ad->news }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 5. TESTIMONIALS SECTION (Social Proof) -->
    <div class="mb-12">
        <div class="text-center mb-12">
            <span class="text-amber-600 font-bold tracking-widest uppercase text-sm">Testimonials</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mt-2">What Our Passengers Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse ($reviews as $review)
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative mt-8 pt-12">
                    <div class="absolute -top-8 left-8 w-16 h-16 bg-slate-800 rounded-2xl border-4 border-white flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-quote-left text-xl"></i>
                    </div>

                    <div class="flex text-amber-400 mb-4">
                        @for($i = 0; $i < ($review->rating ?? 5); $i++)
                            <i class="fas fa-star text-sm mr-1"></i>
                        @endfor
                    </div>
                    <p class="text-slate-600 leading-relaxed italic mb-6">"{{ $review->comment ?? 'A highly comfortable journey with excellent VIP services.' }}"</p>

                    <div class="border-t border-slate-100 pt-4">
                        <h4 class="font-bold text-slate-800">{{ $review->reviewable->name ?? 'Verified Passenger' }}</h4>
                        <span class="text-xs text-slate-400">VIP Traveler</span>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-slate-500 p-12 bg-white rounded-3xl border border-dashed border-slate-300">
                    No reviews yet. Be the first to experience our luxury fleets!
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
