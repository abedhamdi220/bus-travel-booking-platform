<x-app-layout>
    <div class="max-w-4xl mx-auto mb-12 mt-8">

        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="w-20 h-20 bg-amber-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-500/20">
                <i class="fas fa-shield-alt text-3xl text-amber-500"></i>
            </div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Booking Confirmation & Secure Payment</h1>
            <p class="text-slate-500">Your final step to confirm your seat in our luxury fleet</p>
        </div>

        <!-- Glassmorphism Payment Card -->
        <div class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-2xl rounded-3xl p-8 lg:p-12 overflow-hidden relative">
            <!-- Background decoration -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 relative z-10">

                <!-- Invoice Details -->
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-6 border-b border-slate-200 pb-4">Invoice Summary</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Trip Number</span>
                            <span class="font-bold text-slate-800">#{{ $tripId }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Service</span>
                            <span class="font-bold text-slate-800">Business Class Seat Reservation</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Payment Gateway</span>
                            <span class="inline-flex items-center gap-2 font-bold text-slate-800">
                                <i class="fas fa-credit-card text-amber-500"></i> ShamCash
                            </span>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <div class="flex justify-between items-end">
                            <span class="text-lg font-bold text-slate-800">Total Amount</span>
                            <span class="text-4xl font-black text-amber-600">{{ $cost ?? '0' }} <span class="text-base text-slate-500">SYP</span></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Action -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col justify-center text-center">
                    <img src="https://via.placeholder.com/150x50?text=ShamCash+Logo" alt="ShamCash" class="h-12 object-contain mx-auto mb-6 opacity-80">

                    <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                        You will now be redirected to the encrypted <span class="font-bold text-slate-700">ShamCash</span> gateway to complete the transaction with complete security.
                    </p>

                    <form action="{{ route('payment.paysham') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tripNumber" value="{{ $tripId }}">
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-900 font-bold rounded-xl shadow-[0_8px_20px_rgba(245,158,11,0.3)] transform active:scale-95 transition-all duration-300 flex justify-center items-center gap-3 text-lg">
                            <i class="fas fa-lock"></i> Pay Now Securely
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
