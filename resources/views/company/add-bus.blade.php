<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-8 mt-6">
  <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <main class="w-full lg:w-3/4">
            <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-8 lg:p-12">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-800">Add New Bus</h1>
                    <p class="text-slate-500 mt-1">Register a new vehicle to your operational fleet.</p>
                </div>

                <form action="{{ route('company.buses.store') }}" method="POST" class="space-y-6" x-data="{ type: '{{ old('type_vehicle', '') }}' }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Plate Number -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">License Plate Number</label>
                            <input type="text" name="plate_number" value="{{ old('plate_number') }}" required placeholder="e.g. SY-12345"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500 font-mono">
                            @error('plate_number') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bus Type -->
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Vehicle Type</label>
                            <select name="type_vehicle" x-model="type" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                <option value="" disabled>Select vehicle type</option>
                                <option value="nurmal">Regular Class</option>
                                <option value="VIP">VIP Class</option>
                            </select>
                            @error('type_vehicle') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Dynamic Capacity Display -->
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Passenger Capacity</label>
                            <div class="w-full bg-slate-100 border border-slate-200 text-slate-500 rounded-xl py-3 px-4 font-bold flex items-center justify-between">
                                <span x-text="type === 'VIP' ? '35 Seats' : (type === 'nurmal' ? '45 Seats' : 'Select type first')"></span>
                                <i class="fas fa-users"></i>
                            </div>
                        </div>

                        <!-- Initial Status -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Initial Operational Status</label>
                            <select name="state" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl py-3 px-4 focus:ring-amber-500">
                                <option value="availability" {{ old('state') == 'availability' ? 'selected' : '' }}>Available for Deployment</option>
                                <option value="maintenance" {{ old('state') == 'maintenance' ? 'selected' : '' }}>In Maintenance</option>
                            </select>
                            @error('state') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end gap-4">
                        <a href="{{ route('company.buses') }}" class="py-3 px-6 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">Cancel</a>
                        <button type="submit" class="py-3 px-8 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold rounded-xl shadow-md transition-colors flex items-center gap-2">
                            <i class="fas fa-save"></i> Register Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>
