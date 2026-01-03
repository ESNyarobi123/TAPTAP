<x-guest-layout>
    <div class="relative">
        <!-- Decorative Background -->
        <div class="absolute -top-20 -left-20 w-40 h-40 bg-orange-red/10 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-deep-blue/10 rounded-full blur-3xl animate-pulse-slow"></div>

        <div class="relative z-10">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-deep-blue tracking-tight">Karibu Tena!</h2>
                <p class="text-gray-500 font-medium mt-2">Ingia kwenye account yako ya TAPTAP</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="group">
                    <x-input-label for="email" :value="__('Email Address')" class="group-focus-within:text-orange-red transition-colors" />
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-orange-red transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <x-text-input id="email" class="block w-full pl-12 p-4 text-lg rounded-2xl border-2 border-gray-100 focus:border-orange-red transition-all" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="manager@taptap.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="group">
                    <div class="flex justify-between items-center mb-1">
                        <x-input-label for="password" :value="__('Password')" class="group-focus-within:text-orange-red transition-colors" />
                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-orange-red hover:text-deep-blue transition-colors" href="{{ route('password.request') }}">
                                {{ __('Umesahau?') }}
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-orange-red transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <x-text-input id="password" class="block w-full pl-12 p-4 text-lg rounded-2xl border-2 border-gray-100 focus:border-orange-red transition-all"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password"
                                        placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded-lg border-2 border-gray-200 text-orange-red shadow-sm focus:ring-orange-red w-5 h-5 transition-all" name="remember">
                        <span class="ms-3 text-sm font-bold text-gray-600">{{ __('Nikumbuke') }}</span>
                    </label>
                </div>

                <div class="pt-4">
                    <x-primary-button class="w-full justify-center py-5 rounded-2xl text-xl font-black shadow-2xl shadow-orange-red/30 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        {{ __('Ingia Sasa') }}
                    </x-primary-button>
                </div>

                <div class="text-center mt-8">
                    <p class="text-gray-500 font-medium">Huna account bado?</p>
                    <a href="{{ route('restaurant.register') }}" class="text-orange-red font-black hover:text-deep-blue transition-colors">
                        Sajili Restaurant Yako Hapa
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
