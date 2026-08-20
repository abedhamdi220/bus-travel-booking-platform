<!-- Button to open review modal (usage example) -->
<div x-data="{ openReview: false, rating: 0, hoverRating: 0 }" class="inline-block">
    <button @click="openReview = true" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition shadow-sm">
        <i class="fas fa-star text-amber-400 mr-1"></i> Rate the Trip
    </button>

    <!-- The Modal -->
    <div x-show="openReview"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>

        <!-- Dark modal backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openReview = false"></div>

        <!-- Glassmorphism modal content -->
        <div class="relative bg-white/90 backdrop-blur-xl border border-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center">

            <button @click="openReview = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-medal text-3xl text-amber-500"></i>
            </div>

            <h3 class="text-2xl font-bold text-slate-800 mb-2">How was your experience?</h3>
            <p class="text-slate-500 text-sm mb-6">Your feedback helps us improve Sham Fleets services.</p>

            <form action="{{ route('reviews.company') }}" method="POST">
                @csrf
                <!-- Example: Sending Company ID (passed dynamically) -->
                <input type="hidden" name="company_id" value="1">

                <!-- Hidden numeric rating field -->
                <input type="hidden" name="rating" :value="rating" required>

                <!-- Interactive stars with Alpine.js -->
                <div class="flex justify-center gap-2 mb-6" @mouseleave="hoverRating = 0">
                    <template x-for="star in 5">
                        <button type="button"
                                @mouseover="hoverRating = star"
                                @click="rating = star"
                                class="focus:outline-none transition-transform hover:scale-110">
                            <i class="fas fa-star text-4xl transition-colors duration-200"
                               :class="(hoverRating >= star || rating >= star) ? 'text-amber-400 drop-shadow-[0_0_8px_rgba(251,191,36,0.5)]' : 'text-slate-200'"></i>
                        </button>
                    </template>
                </div>

                <!-- Comment field -->
                <div class="mb-6 text-left" dir="ltr">
                    <label class="block text-sm font-medium text-slate-600 mb-2">Add a comment (Optional)</label>
                    <textarea name="comment" rows="3"
                              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:ring-amber-500 focus:border-amber-500 transition resize-none placeholder-slate-400"
                              placeholder="Share the details of your amazing experience..."></textarea>
                </div>

                <!-- Action buttons -->
                <button type="submit" :disabled="rating === 0"
                        class="w-full py-3 rounded-xl font-bold transition-all duration-300"
                        :class="rating > 0 ? 'bg-amber-500 hover:bg-amber-600 text-slate-900 shadow-lg cursor-pointer' : 'bg-slate-200 text-slate-400 cursor-not-allowed'">
                    Submit Review
                </button>
            </form>
        </div>
    </div>
</div>
