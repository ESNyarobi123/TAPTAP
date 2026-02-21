<x-guest-layout title="TIPTAP | Login">
    <div class="relative">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-white tracking-tight">Karibu Tena!</h2>
            <p class="text-white/50 font-medium mt-2">Ingia kwenye account yako ya TIPTAP</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="group">
                <label for="email" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/30 group-focus-within:text-violet-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="manager@tiptap.com"
                           class="block w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="group">
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="text-[10px] font-bold uppercase tracking-wider text-white/40">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs font-bold text-violet-400 hover:text-cyan-400 transition-colors" href="{{ route('password.request') }}">
                            {{ __('Umesahau?') }}
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/30 group-focus-within:text-violet-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                           class="block w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="w-5 h-5 rounded bg-white/5 border-white/20 text-violet-600 focus:ring-violet-500 focus:ring-offset-0 transition-all" name="remember">
                    <span class="ms-3 text-sm font-medium text-white/60">{{ __('Nikumbuke') }}</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-bold text-lg shadow-xl shadow-violet-500/25 hover:shadow-violet-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Ingia Sasa
                </button>
            </div>

            <div class="text-center pt-4">
                <p class="text-white/40 font-medium text-sm">Huna account bado?</p>
                <a href="{{ route('restaurant.register') }}" class="text-violet-400 font-bold hover:text-cyan-400 transition-colors">
                    Sajili Restaurant Yako Hapa
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
