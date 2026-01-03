<x-admin-layout>
    <x-slot name="header">
        Bot Control Center
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($bots as $bot)
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
            <div class="flex justify-between items-start mb-8">
                <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-slate-900/20 group-hover:scale-110 transition-all">
                    <i data-lucide="bot" class="w-8 h-8"></i>
                </div>
                <div class="flex flex-col items-end">
                    @if($bot->status == 'active')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black rounded-full uppercase tracking-widest border border-emerald-100 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Active
                        </span>
                    @else
                        <span class="px-3 py-1 bg-slate-100 text-slate-400 text-[9px] font-black rounded-full uppercase tracking-widest border border-slate-200">Inactive</span>
                    @endif
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-2">Last Ping: {{ $bot->last_ping ? $bot->last_ping->diffForHumans() : 'Never' }}</span>
                </div>
            </div>

            <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-2">{{ $bot->name }}</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-6">Automation & Integration Bot</p>

            <div class="space-y-4 pt-6 border-t border-slate-50">
                <form action="{{ route('admin.bots.update-endpoint') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="bot_id" value="{{ $bot->id }}">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Endpoint URL</label>
                        <div class="flex gap-2">
                            <input type="url" name="endpoint" value="{{ $bot->endpoint }}" class="flex-1 px-4 py-3 bg-slate-50 border-none rounded-xl text-xs font-mono focus:ring-2 focus:ring-slate-900 transition-all" placeholder="https://api.bot.com/webhook">
                            <button type="submit" class="p-3 bg-slate-900 text-white rounded-xl hover:shadow-lg transition-all">
                                <i data-lucide="save" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="flex gap-2 pt-2">
                    <button class="flex-1 py-3 bg-slate-50 text-slate-900 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all">Restart Bot</button>
                    <button class="flex-1 py-3 bg-slate-50 text-slate-900 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all">View Logs</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-[2.5rem] p-12 text-center border border-slate-100 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mx-auto mb-6">
                <i data-lucide="bot" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tighter mb-2">No Bots Configured</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-8">System automation bots will appear here</p>
            <button class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-2xl transition-all">Register New Bot</button>
        </div>
        @endforelse
    </div>
</x-admin-layout>
