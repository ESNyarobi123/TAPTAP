<x-manager-layout>
    <x-slot name="header">
        Payments & Revenue
    </x-slot>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Payments & Revenue</h2>
            <p class="text-sm font-medium text-white/40 uppercase tracking-wider">Track your earnings and payment methods</p>
        </div>
        <div class="flex gap-3">
            <button class="glass px-5 py-3 rounded-xl font-semibold text-white/70 hover:text-white hover:bg-white/10 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                </svg>
                Export Report
            </button>
            <button class="bg-gradient-to-r from-violet-600 to-cyan-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all">
                Withdraw Funds
            </button>
        </div>
    </div>

    <!-- Revenue Chart Placeholder -->
    <div class="glass-card p-8 rounded-2xl mb-8">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-bold text-white tracking-tight">Revenue Overview (Last 7 Days)</h3>
            <select class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 font-semibold text-sm text-white/80 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                <option>This Week</option>
                <option>Last Week</option>
                <option>This Month</option>
            </select>
        </div>
        <div class="h-48 bg-white/5 rounded-2xl flex items-center justify-center border border-dashed border-white/10">
            <p class="text-white/30 font-medium uppercase tracking-wider text-sm">Revenue Chart Visualization</p>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6 rounded-2xl flex items-center gap-5 card-hover">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-xl flex items-center justify-center border border-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-400">
                    <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-white/40 uppercase tracking-wider">Cash Payments</p>
                <p class="text-xl font-bold text-white">Tsh {{ number_format($cashRevenue) }}</p>
            </div>
        </div>
        <div class="glass-card p-6 rounded-2xl flex items-center gap-5 card-hover">
            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-xl flex items-center justify-center border border-cyan-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-400">
                    <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-white/40 uppercase tracking-wider">USSD / Mobile</p>
                <p class="text-xl font-bold text-white">Tsh {{ number_format($mobileRevenue) }}</p>
            </div>
        </div>
        <div class="glass-card p-6 rounded-2xl flex items-center gap-5 card-hover">
            <div class="w-12 h-12 bg-gradient-to-br from-violet-500/20 to-purple-500/20 rounded-xl flex items-center justify-center border border-violet-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-violet-400">
                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-white/40 uppercase tracking-wider">Card Payments</p>
                <p class="text-xl font-bold text-white">Tsh {{ number_format($cardRevenue) }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-white/5">
            <h3 class="text-xl font-bold text-white tracking-tight">Recent Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-5 font-mono text-sm text-white/60">TXN-{{ $payment->id }}</td>
                            <td class="px-6 py-5 font-semibold text-white">Table #{{ $payment->order?->table_number ?? 'N/A' }}</td>
                            <td class="px-6 py-5">
                                <span class="bg-cyan-500/10 text-cyan-400 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase border border-cyan-500/20">{{ $payment->method }}</span>
                            </td>
                            <td class="px-6 py-5 font-bold text-white">Tsh {{ number_format($payment->amount) }}</td>
                            <td class="px-6 py-5">
                                <span class="text-emerald-400 font-semibold flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-white/40 font-medium">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-white/5">
            {{ $payments->links() }}
        </div>
    </div>
</x-manager-layout>
