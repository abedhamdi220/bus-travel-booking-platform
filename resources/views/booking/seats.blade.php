<x-app-layout>
    <!-- Passing seat data from backend to Alpine.js -->
    <div x-data="seatBooking({{ $trip->seats->toJson() }}, {{ $trip->cost }})" class="mb-12">

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Seat Selection</h1>
            <p class="text-slate-500">Luxury trip from <span class="font-bold text-amber-600">{{ $trip->departure_city }}</span> to <span class="font-bold text-amber-600">{{ $trip->destination }}</span></p>
        </div>

        <!-- Display error messages if any -->
        @if($errors->any())
            <div class="max-w-5xl mx-auto mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-2xl text-red-600">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Section: Bus Layout -->
            <div class="lg:col-span-5 bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-8 flex flex-col items-center">
                <div class="w-full flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
                    <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-md bg-slate-200 border border-slate-300"></span><span class="text-xs text-slate-600">Available</span></div>
                    <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-md bg-slate-800"></span><span class="text-xs text-slate-600">Booked</span></div>
                    <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-md bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></span><span class="text-xs text-slate-600">Selected</span></div>
                </div>

                <!-- Bus design (Driver cabin) -->
                <div class="w-full max-w-[280px] bg-slate-100 rounded-t-[3rem] rounded-b-xl border-4 border-slate-300 p-6 shadow-inner relative">
                    <!-- Steering wheel (Adjusted to the left for LTR standard) -->
                    <div class="absolute top-6 left-8 w-8 h-8 border-4 border-slate-400 rounded-full opacity-50"></div>

                    <div class="mt-16 grid grid-cols-4 gap-3 gap-y-4 relative">
                        <!-- Aisle path in the middle -->
                        <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-8 bg-slate-200/50 rounded-full"></div>

                        <!-- Generate seats programmatically via Alpine -->
                        <template x-for="seat in sortedSeats" :key="seat.id">
                            <!-- Add aisle space after the second seat -->
                            <div :class="{'col-start-4': isWindowSeatRight(seat.number_seat)}">
                                <button
                                    type="button"
                                    @click="toggleSeat(seat)"
                                    :disabled="seat.state === 'booked'"
                                    class="w-12 h-12 rounded-t-xl rounded-b-md flex items-center justify-center font-bold text-sm transition-all duration-300 transform"
                                    :class="{
                                        'bg-slate-800 text-slate-400 cursor-not-allowed opacity-60': seat.state === 'booked',
                                        'bg-slate-200 text-slate-600 hover:bg-slate-300 hover:-translate-y-1 hover:shadow-md cursor-pointer border border-slate-300': seat.state !== 'booked' && !isSelected(seat),
                                        'bg-gradient-to-t from-amber-600 to-amber-400 text-white shadow-[0_4px_15px_rgba(245,158,11,0.4)] -translate-y-1 border border-amber-300': isSelected(seat)
                                    }"
                                >
                                    <span x-text="seat.number_seat"></span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Section: Dynamic Passenger Form -->
            <div class="lg:col-span-7">
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgba(0,0,0,0.04)] rounded-3xl p-8 sticky top-24">

                    <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-ticket-alt text-amber-500"></i> Booking Details
                    </h2>

                    <!-- State when no seats are selected -->
                    <div x-show="selectedSeats.length === 0" class="text-center py-12 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-hand-pointer text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500 font-medium">Please select seats from the bus map to start entering passenger details.</p>
                    </div>

                    <!-- Submission Form -->
                    <form x-show="selectedSeats.length > 0" action="{{ route('booking.store', $trip->id) }}" method="POST" class="space-y-6" x-cloak>
                        @csrf

                        <!-- Generate passenger fields dynamically -->
                        <div class="max-h-[400px] overflow-y-auto pr-2 space-y-4 custom-scrollbar">
                            <template x-for="(seat, index) in selectedSeats" :key="seat.id">
                                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 relative group transition-all hover:shadow-md hover:border-amber-200">
                                    <!-- Send seat number -->
                                    <input type="hidden" :name="`bookings[${index}][seatNum]`" :value="seat.number_seat">

                                    <div class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-2xl">
                                        Seat No. <span x-text="seat.number_seat"></span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                        <!-- Phone field -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Passenger Phone Number</label>
                                            <input type="text" :name="`bookings[${index}][phone]`" required
                                                   class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 text-sm"
                                                   placeholder="09XXXXXXXX">
                                        </div>

                                        <!-- Gender field -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Gender</label>
                                            <select :name="`bookings[${index}][gender]`" required
                                                    class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl py-2.5 px-4 focus:ring-amber-500 focus:border-amber-500 text-sm cursor-pointer">
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Price summary and submit button -->
                        <div class="border-t border-slate-200 pt-6 mt-6">
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-slate-600 font-medium">Total Cost (<span x-text="selectedSeats.length"></span> seats):</span>
                                <span class="text-3xl font-black text-amber-600"><span x-text="totalPrice"></span> <span class="text-sm text-slate-500">SYP</span></span>
                            </div>

                            <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold rounded-xl shadow-lg transform active:scale-[0.98] transition duration-300 flex justify-center items-center gap-2 text-lg">
                                <i class="fas fa-lock"></i> Confirm Booking & Proceed to Payment
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js code to control logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('seatBooking', (initialSeats, seatPrice) => ({
                seats: initialSeats,
                selectedSeats: [],
                costPerSeat: seatPrice,

                get sortedSeats() {
                    return this.seats.sort((a, b) => a.number_seat - b.number_seat);
                },

                get totalPrice() {
                    return this.selectedSeats.length * this.costPerSeat;
                },

                toggleSeat(seat) {
                    if (seat.state === 'booked') return;

                    const index = this.selectedSeats.findIndex(s => s.id === seat.id);
                    if (index > -1) {
                        this.selectedSeats.splice(index, 1);
                    } else {
                        // Maximum booking limit (e.g., 5 seats)
                        if(this.selectedSeats.length >= 5) {
                            alert('Sorry, the maximum allowed is 5 seats per booking.');
                            return;
                        }
                        this.selectedSeats.push(seat);
                    }
                },

                isSelected(seat) {
                    return this.selectedSeats.some(s => s.id === seat.id);
                },

                // Simple function to arrange seats (2 right, aisle, 2 left)
                isWindowSeatRight(number) {
                    // If the number is divisible by 4 with a remainder of 3 or 0, it is on the other side of the aisle
                    return number % 4 === 3 || number % 4 === 0;
                }
            }))
        })
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fcd34d; border-radius: 10px; }
    </style>
</x-app-layout>
