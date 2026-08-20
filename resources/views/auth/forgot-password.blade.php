<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('
        نسيت كلمة المرور؟ لا مشكلة! فقط أخبرنا بعنوان بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة المرور يمكنك من خلاله اختيار كلمة مرور جديدة') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="GET" action="{{ route('forgot-password') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- role -->
        <div>
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full" required>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>مستخدم</option>
                <option value="company" {{ old('role') == 'company' ? 'selected' : '' }}>شركة</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('إرسال رابط تعيين كلمة المرور') }}
            </x-primary-button>
        </div>
    </form>
    <style>
        /* #role-field{
    /* shadow: 0 1px 2px 0 ;
    shadow-colored: 0 1px 2px 0 ;
    box-shadow: 0 1px 2px 0 ; /
    /* border-color: rgb(209 213 219); */
    /* border-radius: 0.375rem; */


        } */
    </style>
</x-guest-layout>
