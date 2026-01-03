<x-manager-layout>
    <div class="mb-12">
        <h2 class="text-3xl font-black text-deep-blue tracking-tight">QR & Mobile API</h2>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Connect your restaurant to the TAPTAP network</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
        <!-- QR Code Generator -->
        <div class="bg-white p-12 rounded-[3rem] shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center">
                    <i data-lucide="qr-code" class="w-6 h-6 text-orange-red"></i>
                </div>
                <h3 class="text-2xl font-black text-deep-blue tracking-tight">Table QR Codes</h3>
            </div>
            
            <div class="bg-gray-50 p-10 rounded-[2rem] flex flex-col items-center justify-center mb-8 border-2 border-dashed border-gray-200">
                <div class="w-48 h-48 bg-white p-4 rounded-2xl shadow-xl mb-6 flex items-center justify-center">
                    <!-- Placeholder for QR -->
                    <i data-lucide="qr-code" class="w-32 h-32 text-deep-blue opacity-20"></i>
                </div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Table #05 QR Code</p>
                <button class="bg-deep-blue text-white px-8 py-4 rounded-2xl font-black shadow-lg hover:bg-orange-red transition-all">Download PDF Pack</button>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                    <span class="font-bold text-deep-blue">Total Tables</span>
                    <span class="font-black text-orange-red">24 Tables</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                    <span class="font-bold text-deep-blue">Active Scans Today</span>
                    <span class="font-black text-orange-red">156 Scans</span>
                </div>
            </div>
        </div>

        <!-- ZenoPay Integration -->
        <div class="bg-white p-12 rounded-[3rem] shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
                    <i data-lucide="smartphone" class="w-6 h-6 text-blue-600"></i>
                </div>
                <h3 class="text-2xl font-black text-deep-blue tracking-tight">ZenoPay Mobile Money</h3>
            </div>

            <form action="{{ route('manager.api.zenopay.update') }}" method="POST" class="space-y-8">
                @csrf
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4 block">ZenoPay API Key</label>
                    <div class="relative">
                        <i data-lucide="key" class="w-5 h-5 absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="zenopay_api_key" value="{{ $restaurant->zenopay_api_key }}" placeholder="Enter your ZenoPay API Key" 
                               class="w-full pl-14 pr-6 py-5 bg-gray-50 border-none rounded-[1.5rem] font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>
                </div>

                <div class="p-6 bg-blue-50 rounded-3xl border border-blue-100">
                    <div class="flex gap-4">
                        <i data-lucide="info" class="w-6 h-6 text-blue-600 shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-blue-900 mb-1">How to get your API Key?</p>
                            <p class="text-[10px] text-blue-700 leading-relaxed font-medium">Log in to your ZenoPay dashboard, go to Settings > API Keys, and copy your production key here. This enables USSD push payments for your customers.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-deep-blue text-white py-5 rounded-[1.5rem] font-black text-lg shadow-xl shadow-deep-blue/20 hover:bg-orange-red transition-all">
                    Save ZenoPay Settings
                </button>
            </form>
        </div>
    </div>

    <!-- API Access -->
    <div class="bg-deep-blue p-12 rounded-[3rem] shadow-xl text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-orange-red/10 rounded-full blur-3xl"></div>
        
        <div class="flex items-center gap-4 mb-10 relative z-10">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                <i data-lucide="key" class="w-6 h-6 text-orange-red"></i>
            </div>
            <h3 class="text-2xl font-black tracking-tight">Mobile API Access</h3>
        </div>

        <p class="text-blue-100 mb-10 relative z-10 leading-relaxed">Use these credentials to connect your Node.js bot or custom mobile application to the TAPTAP API.</p>

        <div class="space-y-6 relative z-10">
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Restaurant ID</label>
                <div class="flex gap-2">
                    <input type="text" readonly value="RES-{{ Auth::user()->restaurant_id }}-TAPTAP" class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 flex-1 font-mono text-sm">
                    <button class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-all"><i data-lucide="copy" class="w-4 h-4"></i></button>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">API Secret Token</label>
                <div class="flex gap-2">
                    <input type="password" readonly value="••••••••••••••••••••••••" class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 flex-1 font-mono text-sm">
                    <button class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-all"><i data-lucide="eye" class="w-4 h-4"></i></button>
                </div>
            </div>
        </div>

        <div class="mt-12 p-6 bg-orange-red/20 rounded-2xl border border-orange-red/30 relative z-10">
            <div class="flex gap-4">
                <i data-lucide="alert-circle" class="w-6 h-6 text-orange-red shrink-0"></i>
                <p class="text-xs font-bold leading-relaxed">Keep your API keys secret. Anyone with these keys can manage your restaurant orders and menu.</p>
            </div>
        </div>
    </div>
</x-manager-layout>
