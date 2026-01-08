<x-admin-layout>
    <x-slot name="header">
        Bot Control Center
    </x-slot>

    @if($botToken)
    <div class="mb-8 glass-card rounded-2xl p-8 border border-emerald-500/20 overflow-hidden relative group">
        <div class="absolute top-0 right-0 p-8">
            <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
        </div>
        <h3 class="text-lg font-black text-white tracking-tight mb-2">Active Bot API Token</h3>
        <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest mb-6">Use this token in your bot's .env file as BOT_TOKEN</p>
        
        <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl border border-white/10">
            <code class="flex-1 font-mono text-xs text-emerald-400 break-all">{{ $botToken }}</code>
            <button onclick="navigator.clipboard.writeText('{{ $botToken }}'); alert('Token copied!')" class="p-3 glass text-white rounded-xl hover:bg-white/10 transition-all border border-white/10">
                <i data-lucide="copy" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($bots as $bot)
        <div class="glass-card rounded-2xl p-8 hover:border-violet-500/30 transition-all group">
            <div class="flex justify-between items-start mb-8">
                <div class="w-14 h-14 bg-gradient-to-br from-violet-600 to-cyan-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-violet-500/20 group-hover:scale-110 transition-all">
                    <i data-lucide="bot" class="w-8 h-8"></i>
                </div>
                <div class="flex flex-col items-end">
                    @if($bot->status == 'active')
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-[9px] font-black rounded-full uppercase tracking-widest border border-emerald-500/20 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span> Active
                        </span>
                    @else
                        <span class="px-3 py-1 bg-white/5 text-white/40 text-[9px] font-black rounded-full uppercase tracking-widest border border-white/10">Inactive</span>
                    @endif
                    <span class="text-[9px] text-white/40 font-bold uppercase tracking-widest mt-2">Last Ping: {{ $bot->last_ping ? $bot->last_ping->diffForHumans() : 'Never' }}</span>
                </div>
            </div>

            <h3 class="text-xl font-black text-white tracking-tight mb-2">{{ $bot->name }}</h3>
            <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest mb-6">Automation & Integration Bot</p>

            <div class="space-y-4 pt-6 border-t border-white/5">
                <form action="{{ route('admin.bots.update-endpoint') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="bot_id" value="{{ $bot->id }}">
                    <div class="space-y-2">
                        <label class="text-[9px] font-bold uppercase tracking-wider text-white/40 block">Endpoint URL</label>
                        <div class="flex gap-2">
                            <input type="url" name="endpoint" value="{{ $bot->endpoint }}" class="flex-1 px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-xs font-mono text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all" placeholder="https://api.bot.com/webhook">
                            <button type="submit" class="p-3 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl hover:shadow-lg transition-all">
                                <i data-lucide="save" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="flex gap-2 pt-2">
                    <button class="flex-1 py-3 glass text-white/70 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-violet-600 hover:text-white transition-all">Restart Bot</button>
                    <button class="flex-1 py-3 glass text-white/70 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-violet-600 hover:text-white transition-all">View Logs</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full glass-card rounded-2xl p-12 text-center">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-white/20 mx-auto mb-6 border border-white/10">
                <i data-lucide="bot" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-black text-white tracking-tight mb-2">No Bots Configured</h3>
            <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest mb-8">System automation bots will appear here</p>
            <div class="flex flex-col gap-4 mt-8 max-w-md mx-auto">
                <button class="px-8 py-4 glass text-white rounded-xl font-bold text-sm hover:bg-white/10 transition-all">Register New Bot</button>
                
                <form action="{{ route('admin.bots.generate-token') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-emerald-500/25 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="key" class="w-4 h-4"></i>
                        Generate & Sync Bot Token
                    </button>
                </form>
                <p class="text-[9px] text-white/40 text-center font-bold uppercase tracking-widest">This will update BOT_TOKEN in your .env file</p>
            </div>
        </div>
        @endforelse
    </div>
</x-admin-layout>
