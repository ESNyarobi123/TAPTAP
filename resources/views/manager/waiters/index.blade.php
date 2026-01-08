<x-manager-layout>
    <x-slot name="header">
        Waiters & Staff
    </x-slot>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Waiters & Staff</h2>
            <p class="text-sm font-medium text-white/40 uppercase tracking-wider">Manage your service team</p>
        </div>
        <button onclick="openAddWaiterModal()" class="bg-gradient-to-r from-violet-600 to-cyan-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>
            </svg>
            Add New Waiter
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($waiters as $waiter)
            <div class="glass-card p-6 rounded-2xl card-hover group">
                <div class="flex items-center gap-5 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center font-bold text-2xl text-violet-400 border border-violet-500/20 group-hover:scale-110 transition-transform">
                        {{ substr($waiter->name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-white">{{ $waiter->name }}</h4>
                        <p class="text-[11px] font-medium text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            Active Now
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div class="glass p-4 rounded-xl">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1">Orders</p>
                        <p class="text-xl font-bold text-white">{{ $waiter->orders_count }}</p>
                    </div>
                    <div class="glass p-4 rounded-xl">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1">Tips</p>
                        <p class="text-xl font-bold text-white">Tsh {{ number_format($waiter->tips()->sum('amount')) }}</p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 glass py-2.5 rounded-xl font-semibold text-white/70 hover:text-white hover:bg-violet-600 transition-all text-sm">View Profile</button>
                    <form action="{{ route('manager.waiters.destroy', $waiter->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this waiter?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2.5 glass text-rose-400 rounded-xl hover:bg-rose-500 hover:text-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card py-16 text-center rounded-2xl">
                <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/20">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No waiters found</h3>
                <p class="text-white/40">Add your first waiter to start taking orders.</p>
            </div>
        @endforelse
    </div>

    <!-- Add Waiter Modal -->
    <div id="addWaiterModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-surface-900 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-white/10">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-white tracking-tight">Add New Waiter</h3>
                        <p class="text-sm font-medium text-white/40">Create a new service account</p>
                    </div>
                    <button onclick="closeAddWaiterModal()" class="p-2 hover:bg-white/10 rounded-xl transition-all text-white/40 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('manager.waiters.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Full Name</label>
                        <input type="text" name="name" required placeholder="e.g. John Doe" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Email Address</label>
                        <input type="email" name="email" required placeholder="e.g. john@tiptap.com" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Confirm Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-cyan-600 text-white py-3.5 rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all mt-2">
                        Create Waiter Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddWaiterModal() {
            document.getElementById('addWaiterModal').classList.remove('hidden');
            document.getElementById('addWaiterModal').classList.add('flex');
        }

        function closeAddWaiterModal() {
            document.getElementById('addWaiterModal').classList.add('hidden');
            document.getElementById('addWaiterModal').classList.remove('flex');
        }
    </script>
</x-manager-layout>
