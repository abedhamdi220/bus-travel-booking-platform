<x-guest-layout>
    <div class="max-w-xl mx-auto text-center space-y-5">
        <h1 class="text-2xl font-bold text-red-700">Company Account Rejected</h1>
        <p class="text-slate-600">Your company account has not been approved. Please contact the system administrator for further information.</p>
        <a href="{{ route('login') }}" class="inline-flex rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Return to login</a>
    </div>
</x-guest-layout>
