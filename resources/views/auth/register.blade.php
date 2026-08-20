<x-guest-layout>
    <div x-data="{
        step: {{ $errors->hasAny(['address', 'phone', 'logo', 'infoCompany']) ? 2 : 1 }},
        role: '{{ old('role', 'user') }}',
        password: '',
        password_confirmation: '',
        get passwordsMatch() {
            if (!this.password || !this.password_confirmation) return true;
            return this.password === this.password_confirmation;
        }
    }">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-white tracking-wide">إنشاء حساب جديد</h2>
            <p class="text-slate-300 text-sm mt-1">انضم إلى مجتمع السفر الفاخر والأكثر أماناً</p>
        </div>

        <!-- 🔴 قسم عرض رسائل النجاح والأخطاء المخفية (السبب الرئيسي لمشكلتك) 🔴 -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 rounded-2xl text-sm flex items-center gap-3 backdrop-blur-md">
                <i class="fas fa-check-circle text-xl shrink-0"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/40 text-red-300 rounded-2xl text-sm flex items-start gap-3 backdrop-blur-md shadow-lg">
                <i class="fas fa-exclamation-circle text-xl shrink-0 mt-0.5"></i>
                <span class="font-medium leading-relaxed">{{ session('error') }}</span>
            </div>
        @endif

        <!-- مؤشر الخطوات العلوي -->
        <div class="flex items-center justify-center gap-4 mb-6 text-xs text-slate-400">
            <div class="flex items-center gap-1.5" :class="step === 1 ? 'text-amber-400 font-bold' : 'text-slate-400'">
                <span class="w-5 h-5 rounded-full flex items-center justify-center border border-current">١</span>
                <span>البيانات الأساسية</span>
            </div>
            <div x-show="role === 'company'" class="w-8 h-px bg-white/10"></div>
            <div x-show="role === 'company'" class="flex items-center gap-1.5" :class="step === 2 ? 'text-amber-400 font-bold' : 'text-slate-400'">
                <span class="w-5 h-5 rounded-full flex items-center justify-center border border-current">٢</span>
                <span>الوثائق القانونية</span>
            </div>
        </div>

       <form action="{{ url('/register') }}" method="POST" enctype="multipart/form-data" class="space-y-5" novalidate>
            @csrf

            <!-- الخطوة الأولى: الحساب العام والمسافر -->
            <div x-show="step === 1" class="space-y-4">
                <!-- الاسم -->
                <div>
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">الاسم الكامل</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300 @error('name') border-red-500 @enderror" placeholder="أدخل اسمك أو اسم الشركة">
                    @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- البريد الإلكتروني -->
                <div>
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300 @error('email') border-red-500 @enderror" placeholder="example@domain.com">
                    @error('email') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- كلمة المرور -->
                <div>
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">كلمة المرور</label>
                    <input type="password" name="password" x-model="password" required
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300 @error('password') border-red-500 @enderror" placeholder="••••••••">
                    @error('password') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- تأكيد كلمة المرور -->
                <div>
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" x-model="password_confirmation" required
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300" placeholder="••••••••">
                    <span x-show="!passwordsMatch" class="text-xs text-red-400 mt-1 block font-medium">كلمتا المرور غير متطابقتين!</span>
                </div>

                <!-- الدور -->
                <div>
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">التسجيل بصفتي</label>
                    <select name="role" x-model="role" required
                            class="w-full px-4 py-3 bg-slate-800 border border-white/10 rounded-xl text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300 cursor-pointer">
                        <option value="user">مسافر (Passenger)</option>
                        <option value="company">شركة نقل (Company)</option>
                    </select>
                </div>

                <!-- الجنس (خاص بالمسافر فقط) -->
                <div x-show="role === 'user'">
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">الجنس</label>
                    <select name="gender" :required="role === 'user'"
                            class="w-full px-4 py-3 bg-slate-800 border border-white/10 rounded-xl text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition duration-300 cursor-pointer">
                        <option value="">اختر الجنس</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                    @error('gender') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- زر الإرسال للمسافر أو زر التالي للشركة -->
                <template x-if="role === 'user'">
                    <button type="submit" :disabled="!passwordsMatch" class="w-full py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold rounded-xl shadow-lg transition duration-300 cursor-pointer text-base">
                        إتمام التسجيل
                    </button>
                </template>

                <template x-if="role === 'company'">
                    <button type="button" @click="if(passwordsMatch && password) step = 2" class="w-full py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold rounded-xl shadow-lg transition duration-300 cursor-pointer text-base">
                        التالي <i class="fas fa-arrow-left mr-1"></i>
                    </button>
                </template>
            </div>

            <!-- الخطوة الثانية: بيانات ومستندات الشركة الإضافية -->
            <div x-show="step === 2 && role === 'company'" class="space-y-4" x-cloak>
                <!-- العنوان -->
                <div>
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">المقر الرئيسي للشركة</label>
                    <input type="text" name="address" value="{{ old('address') }}" :required="role === 'company'"
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 transition duration-300 @error('address') border-red-500 @enderror" placeholder="المدينة، الشارع">
                    @error('address') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- الهاتف -->
                <div>
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">رقم الهاتف التجاري</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" :required="role === 'company'"
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 transition duration-300 @error('phone') border-red-500 @enderror" placeholder="+9639xxxxxxxx">
                    @error('phone') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- الشعار اللوجو -->
                <div>
                    <label class="block text-sm font-medium text-slate-200 mb-1.5">شعار الشركة (Logo)</label>
                    <input type="file" name="logo" accept="image/*" :required="role === 'company'"
                           class="w-full text-sm text-slate-300 file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-500/10 file:text-amber-400 hover:file:bg-amber-500/20 file:cursor-pointer border border-white/10 rounded-xl p-2 bg-white/5">
                    @error('logo') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- المستندات القانونية -->
                <div x-data="{ popover: false }" class="relative">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-200 mb-1.5">
                        <span>رفع الأوراق والمستندات الرسمية للشركة</span>
                    </label>
                    <input type="file" name="infoCompany" accept=".pdf,.jpg,.png" :required="role === 'company'"
                           class="w-full text-sm text-slate-300 file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-500/10 file:text-amber-400 hover:file:bg-amber-500/20 file:cursor-pointer border border-white/10 rounded-xl p-2 bg-white/5">
                    @error('infoCompany') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- أزرار الخطوة الثانية للشركة -->
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="step = 1" class="w-1/3 py-3 border border-white/10 text-slate-200 font-medium rounded-xl hover:bg-white/5 transition cursor-pointer text-center">
                        <i class="fas fa-arrow-right ml-1"></i> رجوع
                    </button>
                    <button type="submit" class="w-2/3 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold rounded-xl shadow-lg transition duration-300 cursor-pointer text-center">
                        إرسال الطلب للتفعيل
                    </button>
                </div>
            </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-white/10 text-sm text-slate-400">
            لديك حساب بالفعل؟ <a href="{{ url('/login') }}" class="text-amber-400 hover:text-amber-300 transition font-medium">سجل دخولك هنا</a>
        </div>
    </div>
</x-guest-layout>
