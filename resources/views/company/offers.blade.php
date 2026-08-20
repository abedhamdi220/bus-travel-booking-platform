<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-8 mt-6">

  <!-- استدعاء القائمة الجانبية الموحدة -->
        @include('components.company-sidebar')

        <!-- Main Content -->
        <main class="w-full lg:w-3/4 space-y-6" x-data="{ activeTab: 'offers', openOfferModal: false }">

            <div class="flex justify-between items-center mb-2">
                <h1 class="text-3xl font-bold text-slate-800">Marketing & Announcements</h1>
                <button @click="openOfferModal = true" class="bg-slate-800 hover:bg-slate-900 text-amber-400 font-bold py-2.5 px-6 rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i> Publish News/Offer
                </button>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tabs -->
            <div class="flex gap-2 border-b border-slate-200 pb-4">
                <button @click="activeTab = 'offers'" :class="activeTab === 'offers' ? 'bg-amber-500 text-slate-900 shadow-md' : 'bg-white text-slate-500 hover:bg-slate-50'" class="px-6 py-2 rounded-xl font-bold transition-all">Company News & Offers</button>
            </div>

            <!-- Offers Tab Content -->
            <div x-show="activeTab === 'offers'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($ads as $ad)
                    <div class="bg-white/80 backdrop-blur-xl border border-white shadow-sm rounded-3xl p-6 relative overflow-hidden group hover:shadow-lg transition-all">
                        <div class="absolute right-0 top-0 w-16 h-16 bg-amber-100 rounded-bl-full -z-10 group-hover:scale-150 transition-transform"></div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider">Announcement</span>
                            <span class="text-xs text-slate-400 font-medium"><i class="far fa-clock"></i> {{ $ad->created_at->diffForHumans() }}</span>
                        </div>

                        <h4 class="text-lg font-bold text-slate-800 mb-2">Company Update</h4>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ $ad->news }}</p>

                        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                            <!-- Action: Delete -->
                           <form action="{{ route('company.offers.delete', $ad->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold transition flex items-center gap-1">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-16 bg-white/50 border border-dashed border-slate-300 rounded-3xl">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <i class="fas fa-bullhorn text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-700">No Announcements Yet</h4>
                        <p class="text-slate-500 text-sm mt-1">Keep your passengers updated with latest offers and news.</p>
                    </div>
                @endforelse
            </div>

            <!-- Create Offer Modal -->
            <div x-show="openOfferModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openOfferModal = false"></div>
                <div class="relative bg-white border border-slate-200 rounded-3xl shadow-2xl w-full max-w-lg p-8">
                    <button @click="openOfferModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Publish New Content</h3>
                    <p class="text-slate-500 text-sm mb-6">Write your announcement or promotional offer below.</p>

                    <form action="{{ route('company.offers.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-600 mb-2">Announcement Content</label>
                            <textarea name="news" rows="4" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 focus:ring-amber-500 focus:border-amber-500 resize-none" placeholder="e.g., Enjoy a 20% discount on all trips to Damascus this weekend!"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold rounded-xl shadow-lg transition-colors">
                            Publish Now
                        </button>
                    </form>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>
