<x-manager-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-3xl font-black text-deep-blue tracking-tight">Payments & Revenue</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Track your earnings and payment methods</p>
        </div>
        <div class="flex gap-4">
            <button class="bg-white border border-gray-100 px-6 py-4 rounded-2xl font-bold text-gray-600 hover:border-orange-red transition-all flex items-center gap-2">
                <i data-lucide="download" class="w-5 h-5"></i> Export Report
            </button>
            <button class="bg-orange-red text-white px-8 py-4 rounded-2xl font-black text-lg shadow-xl shadow-orange-red/30 hover:bg-deep-blue transition-all">
                Withdraw Funds
            </button>
        </div>
    </div>

    <!-- Revenue Chart Placeholder -->
    <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 mb-12">
        <div class="flex items-center justify-between mb-10">
            <h3 class="text-xl font-black text-deep-blue tracking-tight">Revenue Overview (Last 7 Days)</h3>
            <select class="bg-gray-50 border-none rounded-xl px-4 py-2 font-bold text-sm text-gray-600">
                <option>This Week</option>
                <option>Last Week</option>
                <option>This Month</option>
            </select>
        </div>
        <div class="h-64 bg-gray-50 rounded-[2rem] flex items-center justify-center border-2 border-dashed border-gray-200">
            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Revenue Chart Visualization</p>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 flex items-center gap-6">
            <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center">
                <i data-lucide="banknote" class="w-7 h-7 text-green-500"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cash Payments</p>
                <p class="text-xl font-black text-deep-blue">Tsh {{ number_format($cashRevenue) }}</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 flex items-center gap-6">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center">
                <i data-lucide="smartphone" class="w-7 h-7 text-blue-500"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">USSD / Mobile</p>
                <p class="text-xl font-black text-deep-blue">Tsh {{ number_format($mobileRevenue) }}</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 flex items-center gap-6">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center">
                <i data-lucide="credit-card" class="w-7 h-7 text-orange-red"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Card Payments</p>
                <p class="text-xl font-black text-deep-blue">Tsh {{ number_format($cardRevenue) }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50">
            <h3 class="text-xl font-black text-deep-blue tracking-tight">Recent Transactions</h3>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Transaction ID</th>
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order</th>
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Method</th>
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                    <tr>
                        <td class="px-10 py-6 font-mono text-xs text-gray-500">TXN-{{ $payment->id }}</td>
                        <td class="px-10 py-6 font-bold text-deep-blue">Table #{{ $payment->order->table_number }}</td>
                        <td class="px-10 py-6">
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-3 py-1 rounded-full uppercase">{{ $payment->method }}</span>
                        </td>
                        <td class="px-10 py-6 font-black text-deep-blue">Tsh {{ number_format($payment->amount) }}</td>
                        <td class="px-10 py-6">
                            <span class="text-green-500 font-bold flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-10 py-12 text-center text-gray-400 font-bold">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-6">
            {{ $payments->links() }}
        </div>
    </div>
</x-manager-layout>
