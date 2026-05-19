<x-guest-layout title="TIPTAP ORDER | Login" :hero-background="true">
    <div class="relative">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-[#12141C] tracking-tight">TIPTAP ORDER</h2>
            <p class="text-[#64708B] font-medium mt-2">Live Orders Portal · Sign in with the password from your manager (your restaurant is detected automatically)</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('order-portal.login') }}" class="space-y-6">
            @csrf

            <div class="group">
                <label for="password" class="text-[10px] font-bold uppercase tracking-wider text-[#64708B] mb-2 block">Order Portal password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autofocus placeholder="Enter the password from your manager"
                           class="block w-full px-4 py-4 bg-[#F5F3FF] border border-[#DDD7FE] rounded-xl font-medium text-[#12141C] placeholder-[#64708B]/50 focus:ring-2 focus:ring-[#8C71F6] focus:border-[#8C71F6] transition-all">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-fin w-full py-4 text-white rounded-xl font-bold text-lg hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Sign in to Live Orders
                </button>
            </div>

            <p class="text-center text-[#64708B] text-xs">Your password alone opens Live Orders for your restaurant. When you are unlinked, the password expires.</p>
        </form>
    </div>
</x-guest-layout>
