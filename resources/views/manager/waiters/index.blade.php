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
                <div class="flex items-center gap-5 mb-4">
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

                <!-- Service Tag & QR Section -->
                @if($waiter->waiter_code)
                <div class="mb-4 bg-white/5 rounded-xl overflow-hidden border border-white/10">
                    <!-- Tag Header -->
                    <div class="p-3 bg-white/5 border-b border-white/5 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Service Tag</span>
                        <div class="flex items-center gap-2">
                            <code class="text-sm font-mono font-bold text-cyan-400">{{ $waiter->waiter_code }}</code>
                            <button onclick="copyToClipboard('{{ $waiter->waiter_code }}', this)" class="text-white/40 hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- QR Code Display -->
                    <div class="p-4 flex items-center gap-4">
                        <div class="bg-white p-2 rounded-lg shrink-0">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($waiter->waiter_qr_url) }}" alt="QR" class="w-16 h-16">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] text-white/40 mb-2 truncate">Scan to order with {{ explode(' ', $waiter->name)[0] }}</p>
                            <div class="flex gap-2">
                                <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode($waiter->waiter_qr_url) }}" download="waiter-{{ $waiter->waiter_code }}-qr.png" target="_blank" class="flex-1 px-3 py-2 bg-violet-600 hover:bg-violet-500 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg text-center transition-colors">
                                    Download
                                </a>
                                <button onclick="copyToClipboard('{{ $waiter->waiter_qr_url }}', this)" class="px-3 py-2 glass hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors">
                                    Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="glass p-4 rounded-xl">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1">Orders</p>
                        <p class="text-xl font-bold text-white">{{ $waiter->orders_count }}</p>
                    </div>
                    {{-- Tips are not shown to manager --}}
                </div>

                <div class="flex gap-2">
                    <button onclick="openViewWaiterModal({{ json_encode($waiter) }})" class="flex-1 glass py-2.5 rounded-xl font-semibold text-white/70 hover:text-white hover:bg-violet-600 transition-all text-sm">View Profile</button>
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

    <!-- View Waiter Modal -->
    <div id="viewWaiterModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-surface-900 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-white/10">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-white tracking-tight">Waiter Profile</h3>
                        <p class="text-sm font-medium text-white/40">Staff details and performance</p>
                    </div>
                    <button onclick="closeViewWaiterModal()" class="p-2 hover:bg-white/10 rounded-xl transition-all text-white/40 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center gap-5 mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center font-bold text-3xl text-violet-400 border border-violet-500/20" id="viewWaiterInitial">
                        <!-- Initial JS -->
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold text-white" id="viewWaiterName">--</h4>
                        <p class="text-sm text-white/40" id="viewWaiterEmail">--</p>
                        <div class="mt-2 inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse mr-2"></span>
                            Active Staff
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1">Service Tag</p>
                        <p class="text-lg font-mono font-bold text-cyan-400" id="viewWaiterCode">--</p>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1">Joined Date</p>
                        <p class="text-lg font-bold text-white" id="viewWaiterJoined">--</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-violet-500/10 p-4 rounded-xl border border-violet-500/20">
                        <p class="text-[10px] font-bold text-violet-300 uppercase tracking-wider mb-1">Total Orders</p>
                        <p class="text-2xl font-bold text-white" id="viewWaiterOrders">0</p>
                    </div>
                    {{-- Tips are not shown to manager --}}
                </div>

                <button onclick="closeViewWaiterModal()" class="w-full bg-white/10 text-white py-3.5 rounded-xl font-semibold hover:bg-white/20 transition-all">
                    Close Profile
                </button>
            </div>
        </div>
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

        function openViewWaiterModal(waiter) {
            document.getElementById('viewWaiterName').textContent = waiter.name;
            document.getElementById('viewWaiterEmail').textContent = waiter.email;
            document.getElementById('viewWaiterCode').textContent = waiter.waiter_code || 'N/A';
            document.getElementById('viewWaiterInitial').textContent = waiter.name.charAt(0);
            document.getElementById('viewWaiterOrders').textContent = waiter.orders_count;
            
            // Format Date
            const date = new Date(waiter.created_at);
            document.getElementById('viewWaiterJoined').textContent = date.toLocaleDateString('en-GB', {
                day: 'numeric', month: 'short', year: 'numeric'
            });

            document.getElementById('viewWaiterModal').classList.remove('hidden');
            document.getElementById('viewWaiterModal').classList.add('flex');
        }

        function closeViewWaiterModal() {
            document.getElementById('viewWaiterModal').classList.add('hidden');
            document.getElementById('viewWaiterModal').classList.remove('flex');
        }

        async function copyToClipboard(text, button) {
            try {
                await navigator.clipboard.writeText(text);
                
                // Visual feedback
                const originalContent = button.innerHTML;
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-400">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                `;
                button.classList.add('bg-emerald-500/20', 'border-emerald-500/30');
                
                setTimeout(() => {
                    button.innerHTML = originalContent;
                    button.classList.remove('bg-emerald-500/20', 'border-emerald-500/30');
                }, 2000);
                
            } catch (err) {
                console.error('Failed to copy:', err);
                alert('Failed to copy to clipboard');
            }
        }
    </script>
</x-manager-layout>
