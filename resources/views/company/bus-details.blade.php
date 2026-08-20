<x-app-layout>
    <div class="max-w-5xl mx-auto mt-6 space-y-6">
<!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')
        <div class="flex items-center justify-between">
            <a href="{{ route('company.buses') }}" class="text-slate-500 hover:text-amber-600 font-medium transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Fleet
            </a>
            <form action="{{ route('company.buses.delete', $vehicle->id) }}" method="POST" onsubmit="return confirm('Permanently delete this bus?');">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-bold transition">
                    <i class="fas fa-trash mr-1"></i> Delete Bus
                </button>
            </form>
        </div>

        <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-8">
            <div class="flex flex-col md:flex-row gap-8 items-start">

                <div class="w-full md:w-1/3 aspect-square bg-slate-100 rounded-2xl border-4 border-white shadow-inner flex items-center justify-center text-slate-300 relative overflow-hidden">
                    <i class="fas fa-bus text-6xl"></i>
                    @if($vehicle->state === 'availability')
                        <div class="absolute top-4 right-4 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white shadow-sm"></div>
                    @else
                        <div class="absolute top-4 right-4 w-4 h-4 bg-red-500 rounded-full border-2 border-white shadow-sm"></div>
                    @endif
                </div>

                <div class="flex-1 w-full space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-4xl font-black text-slate-800">Bus #{{ $vehicle->vehicle_number }}</h1>
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider">{{ $vehicle->type_vehicle === 'nurmal' ? 'Regular' : 'VIP' }}</span>
                        </div>
                        <p class="text-slate-500 font-mono text-lg tracking-widest"><i class="fas fa-id-card mr-2"></i>{{ $vehicle->plate_number }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-100">
                        <div>
                            <span class="block text-xs text-slate-400 font-medium uppercase mb-1">Status</span>
                            <span class="font-bold text-slate-700">{{ ucfirst($vehicle->state) }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400 font-medium uppercase mb-1">Capacity</span>
                            <span class="font-bold text-slate-700">{{ $vehicle->type_vehicle === 'VIP' ? '35' : '45' }} Passengers</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400 font-medium uppercase mb-1">Added to Fleet</span>
                            <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($vehicle->date_work)->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400 font-medium uppercase mb-1">Total Trips</span>
                            <span class="font-bold text-slate-700">0 (Operational Logic Pending)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
