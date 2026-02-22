<x-guest-layout title="TIPTAP | Waiter Registration">
    <div class="relative max-w-md mx-auto">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-white tracking-tight">Sajili kama Waiter</h2>
            <p class="text-white/50 font-medium mt-2">Jisajili na upate nambari yako ya pekee. Manager atakuunga na restaurant (muda mrefu au show-time).</p>
        </div>

        @if (session('status'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('waiter.register.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="group">
                    <label for="first_name" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Jina la kwanza</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                           class="block w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                           placeholder="John">
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>
                <div class="group">
                    <label for="last_name" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Jina la mwisho</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                           class="block w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                           placeholder="Doe">
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </div>

            <div class="group">
                <label for="email" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Barua pepe</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="block w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                       placeholder="john@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="group">
                <label for="phone" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Nambari ya simu</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                       class="block w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                       placeholder="0712 345 678">
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div class="group">
                <label for="location" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Mahali (optional)</label>
                <input id="location" type="text" name="location" value="{{ old('location') }}"
                       class="block w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                       placeholder="Dar es Salaam">
                <x-input-error :messages="$errors->get('location')" class="mt-2" />
            </div>

            <div class="group">
                <label for="password" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Neno la siri</label>
                <input id="password" type="password" name="password" required
                       class="block w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="group">
                <label for="password_confirmation" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Thibitisha neno la siri</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="block w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all"
                       placeholder="••••••••">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-bold text-lg shadow-xl shadow-violet-500/25 hover:shadow-violet-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Fungua Akaunti
                </button>
            </div>

            <div class="text-center pt-4 space-y-2">
                <p class="text-white/40 font-medium text-sm">Tayari una akaunti?</p>
                <a href="{{ route('login') }}" class="text-violet-400 font-bold hover:text-cyan-400 transition-colors block">
                    Ingia hapa
                </a>
                <a href="{{ route('restaurant.register') }}" class="text-white/50 text-sm hover:text-white/80 transition-colors block">
                    Sajili Restaurant (Manager)
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
