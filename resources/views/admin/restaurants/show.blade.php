<x-admin-layout>
    <x-slot name="header">
        Restaurant Details
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-10">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-slate-900 rounded-[2rem] flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-slate-900/20">
                            {{ substr($restaurant->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-slate-900 tracking-tighter">{{ $restaurant->name }}</h2>
                            <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-xs mt-1">{{ $restaurant->location ?? 'No location set' }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="px-6 py-3 bg-slate-50 text-slate-900 rounded-2xl font-bold text-sm hover:bg-slate-900 hover:text-white transition-all flex items-center gap-2">
                            <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Details
                        </a>
                        <form action="{{ route('admin.restaurants.toggle-status', $restaurant) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-3 {{ $restaurant->is_active ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }} rounded-2xl font-bold text-sm hover:opacity-80 transition-all flex items-center gap-2">
                                <i data-lucide="{{ $restaurant->is_active ? 'slash' : 'check-circle' }}" class="w-4 h-4"></i>
                                {{ $restaurant->is_active ? 'Block Restaurant' : 'Unblock Restaurant' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 py-8 border-t border-slate-50">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Phone Number</p>
                        <p class="text-slate-900 font-bold">{{ $restaurant->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Joined Date</p>
                        <p class="text-slate-900 font-bold">{{ $restaurant->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="mt-8 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Payment API Configuration</h4>
                        <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[9px] font-black rounded-full uppercase tracking-widest">ZenoPay</span>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">API Key</p>
                            <div class="flex items-center gap-3">
                                <code class="flex-1 bg-white p-3 rounded-xl border border-slate-200 text-xs font-mono text-slate-600 truncate">
                                    {{ $restaurant->zenopay_api_key ?? 'Not Configured' }}
                                </code>
                                <button class="p-3 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-slate-900 transition-all">
                                    <i data-lucide="copy" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Management -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50">
                    <h3 class="text-xl font-black text-slate-900 tracking-tighter">Staff Management</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Managers and Waiters</p>
                </div>
                
                <div class="p-8 space-y-8">
                    <!-- Managers -->
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="user-check" class="w-3 h-3 text-blue-500"></i> Managers ({{ $managers->count() }})
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($managers as $manager)
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center font-black text-xs">
                                    {{ substr($manager->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $manager->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $manager->email }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Waiters -->
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="user" class="w-3 h-3 text-orange-500"></i> Waiters ({{ $waiters->count() }})
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($waiters as $waiter)
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center font-black text-xs">
                                    {{ substr($waiter->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $waiter->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $waiter->email }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="space-y-8">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-6">Financial Overview</h3>
                <div class="space-y-6">
                    <div class="p-6 bg-emerald-50 rounded-[2rem] border border-emerald-100">
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Total Earnings</p>
                        <p class="text-3xl font-black text-emerald-700 tracking-tighter">Tsh 1.2M</p>
                    </div>
                    <div class="p-6 bg-blue-50 rounded-[2rem] border border-blue-100">
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Total Orders</p>
                        <p class="text-3xl font-black text-blue-700 tracking-tighter">156</p>
                    </div>
                    <div class="p-6 bg-purple-50 rounded-[2rem] border border-purple-100">
                        <p class="text-[10px] font-black text-purple-600 uppercase tracking-widest mb-1">Avg. Rating</p>
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-black text-purple-700 tracking-tighter">4.8</p>
                            <i data-lucide="star" class="w-5 h-5 text-purple-400 fill-purple-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-slate-900/20">
                <h3 class="text-xl font-black tracking-tighter mb-6">Quick Actions</h3>
                <div class="space-y-4">
                    <button class="w-full flex items-center gap-4 p-4 bg-white/10 rounded-2xl hover:bg-white/20 transition-all group border border-white/5">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-all">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-sm">Email Manager</p>
                            <p class="text-[10px] text-white/50 font-medium">Send direct message</p>
                        </div>
                    </button>
                    <button class="w-full flex items-center gap-4 p-4 bg-white/10 rounded-2xl hover:bg-white/20 transition-all group border border-white/5">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-all">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-sm">View Reports</p>
                            <p class="text-[10px] text-white/50 font-medium">Download performance</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
