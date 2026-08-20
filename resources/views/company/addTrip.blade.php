<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-8 mt-6">


   <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <main class="w-full lg:w-3/4">
            <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-8 lg:p-12">
                <div class="mb-8 border-b border-slate-100 pb-6">
                    <h1 class="text-3xl font-bold text-slate-800"><i class="fas fa-route text-amber-500 mr-2"></i> Schedule New Trip</h1>
                    <p class="text-slate-500 mt-2">Plan a new journey, assign a professional driver, and deploy a luxury bus.</p>
                </div>

                <!-- 💡 Alpine.js للتحكم الديناميكي بتصفية الباصات -->
             <!-- 💡 تم تمرير الباصات كـ JSON ليقوم Alpine.js بفلترتها بشكل حقيقي وآمن -->
                <form action="{{ route('company.trip.store') }}" method="POST" class="space-y-8"
                      x-data="{
                          tripType: '{{ old('tripType', '') }}',
                          allBuses: {{ Js::from($vehicles) }},
                          get filteredBuses() {
                              return this.allBuses.filter(bus => bus.type_vehicle === this.tripType);
                          }
                      }">
                    @csrf

                    <!-- 1. Route Information -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-700 mb-4">Route Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Departure City</label>
                                <input type="text" name="departure_city" value="{{ old('departure_city') }}" required placeholder="e.g. Damascus" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('departure_city') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Destination City</label>
                                <input type="text" name="destination" value="{{ old('destination') }}" required placeholder="e.g. Aleppo" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('destination') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 2. Schedule & Pricing -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-700 mb-4">Schedule & Pricing</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Departure Date</label>
                                <input type="date" name="dateTrip" value="{{ old('dateTrip') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('dateTrip') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Departure Time</label>
                                <input type="time" name="timeTrip" value="{{ old('timeTrip') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('timeTrip') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Ticket Price (SYP)</label>
                                <input type="number" name="cost" value="{{ old('cost') }}" required placeholder="25000" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                @error('cost') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 3. Fleet & Crew Assignment -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-700 mb-4">Fleet & Crew Assignment</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Trip Type -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Trip Class</label>
                                <select name="tripType" x-model="tripType" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500 cursor-pointer">
                                    <option value="" disabled>Select Class</option>
                                    <option value="nurmal">Regular Class (45 Seats)</option>
                                    <option value="VIP">VIP Luxury Class (35 Seats)</option>
                                </select>
                                @error('tripType') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Driver Selection -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Assign Captain (Driver)</label>
                                <select name="driver_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500 cursor-pointer">
                                    <option value="" disabled selected>Select Available Driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->user->name ?? 'Unknown' }} - {{ $driver->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                @if($drivers->isEmpty()) <span class="text-xs text-amber-500 mt-1 block"><i class="fas fa-exclamation-triangle"></i> No available drivers currently.</span> @endif
                            </div>

                            <!-- Bus Selection (Filtered dynamically via Alpine arrays) -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Assign Vehicle</label>
                                <select name="vehicle_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500 cursor-pointer">

                                    <!-- خيار افتراضي يظهر عندما لا يتم اختيار نوع الرحلة بعد -->
                                    <option value="" disabled selected x-show="!tripType">Select Trip Class First</option>

                                    <!-- تنبيه في حال تم اختيار نوع الرحلة ولا يوجد باصات مطابقة -->
                                    <option value="" disabled selected x-show="tripType && filteredBuses.length === 0">No available buses for this class</option>

                                    <!-- التوليد الديناميكي للباصات المطابقة فقط -->
                                    <template x-for="bus in filteredBuses" :key="bus.id">
                                        <option :value="bus.id" x-text="`Bus #${bus.vehicle_number} (${bus.plate_number})`" :selected="old('vehicle_id') == bus.id"></option>
                                    </template>

                                </select>
                                @error('vehicle_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                @if($vehicles->isEmpty()) <span class="text-xs text-amber-500 mt-1 block"><i class="fas fa-exclamation-triangle"></i> No available buses currently.</span> @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end gap-4">
                        <a href="{{ route('company.trips') }}" class="py-3 px-6 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">Cancel</a>
                        <button type="submit" class="py-3 px-8 bg-slate-800 hover:bg-slate-900 text-amber-400 font-bold rounded-xl shadow-md transition-colors flex items-center gap-2">
                            <i class="fas fa-calendar-check"></i> Deploy Trip
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>
