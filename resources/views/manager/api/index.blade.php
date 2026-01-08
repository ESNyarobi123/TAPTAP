<x-manager-layout>
    <x-slot name="header">
        QR & Mobile API
    </x-slot>

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white tracking-tight">QR & Mobile API</h2>
        <p class="text-sm font-medium text-white/40 uppercase tracking-wider">Connect your restaurant to the TIPTAP network</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- QR Code Generator -->
        <div class="glass-card p-8 rounded-2xl">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center border border-violet-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-violet-400">
                        <rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white tracking-tight">Table QR Codes</h3>
            </div>
            
            <div class="glass p-8 rounded-xl flex flex-col items-center justify-center mb-6 border border-dashed border-white/20">
                <div class="w-40 h-40 bg-white p-3 rounded-xl shadow-xl mb-5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round" class="text-surface-900 opacity-30">
                        <rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-white/40 uppercase tracking-wider mb-5">Table #05 QR Code</p>
                <button class="bg-gradient-to-r from-violet-600 to-cyan-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all">Download PDF Pack</button>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 glass rounded-xl">
                    <span class="font-medium text-white/70">Total Tables</span>
                    <span class="font-bold text-white">24 Tables</span>
                </div>
                <div class="flex items-center justify-between p-4 glass rounded-xl">
                    <span class="font-medium text-white/70">Active Scans Today</span>
                    <span class="font-bold text-white">156 Scans</span>
                </div>
            </div>
        </div>

        <!-- ZenoPay Integration -->
        <div class="glass-card p-8 rounded-2xl">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-xl flex items-center justify-center border border-cyan-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-400">
                        <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white tracking-tight">ZenoPay Mobile Money</h3>
            </div>

            <form action="{{ route('manager.api.zenopay.update') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-3 block">ZenoPay API Key</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                            <circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/>
                        </svg>
                        <input type="password" name="zenopay_api_key" value="{{ $restaurant->zenopay_api_key }}" placeholder="Enter your ZenoPay API Key" 
                               class="w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="p-5 bg-cyan-500/10 rounded-xl border border-cyan-500/20">
                    <div class="flex gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-400 shrink-0">
                            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-cyan-400 mb-1">How to get your API Key?</p>
                            <p class="text-[11px] text-white/60 leading-relaxed">Log in to your ZenoPay dashboard, go to Settings > API Keys, and copy your production key here. This enables USSD push payments for your customers.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-cyan-600 text-white py-3.5 rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all">
                    Save ZenoPay Settings
                </button>
            </form>
        </div>
    </div>

    <!-- API Access -->
    <div class="glass-card p-8 rounded-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-violet-500/10 to-cyan-500/10 rounded-full blur-3xl"></div>
        
        <div class="flex items-center gap-4 mb-8 relative z-10">
            <div class="w-12 h-12 bg-gradient-to-br from-violet-500/20 to-purple-500/20 rounded-xl flex items-center justify-center border border-violet-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-violet-400">
                    <circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white tracking-tight">Mobile API Access</h3>
        </div>

        <p class="text-white/60 mb-8 relative z-10 leading-relaxed">Use these credentials to connect your Node.js bot or custom mobile application to the TIPTAP API.</p>

        <div class="space-y-4 relative z-10">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Restaurant ID</label>
                <div class="flex gap-2">
                    <input type="text" readonly value="RES-{{ Auth::user()->restaurant_id }}-TIPTAP" class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 flex-1 font-mono text-sm text-white">
                    <button class="p-3 glass rounded-xl hover:bg-white/10 transition-all text-white/60 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">API Secret Token</label>
                <div class="flex gap-2">
                    <input type="password" readonly value="••••••••••••••••••••••••" class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 flex-1 font-mono text-sm text-white">
                    <button class="p-3 glass rounded-xl hover:bg-white/10 transition-all text-white/60 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-8 p-5 bg-rose-500/10 rounded-xl border border-rose-500/20 relative z-10">
            <div class="flex gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-400 shrink-0">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>
                </svg>
                <p class="text-sm font-medium text-white/80">Keep your API keys secret. Anyone with these keys can manage your restaurant orders and menu.</p>
            </div>
        </div>
    </div>
</x-manager-layout>
