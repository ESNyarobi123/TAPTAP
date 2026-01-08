<x-admin-layout>
    <x-slot name="header">
        Payments & Transactions
    </x-slot>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-white/5 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-white tracking-tight">Transaction History</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mt-1">Monitor all financial activities across the platform</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Transaction ID</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Order</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Restaurant</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Method</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-white/5 transition-all">
                        <td class="px-6 py-5">
                            <span class="font-mono text-xs text-white/60 uppercase">{{ $payment->transaction_id ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="font-bold text-white">#{{ str_pad($payment->order_id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-white font-bold">{{ $payment->order->restaurant->name }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-white font-black">Tsh {{ number_format($payment->amount, 0) }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-white/10 text-white/70 text-[10px] font-black rounded-full uppercase tracking-widest border border-white/10">{{ $payment->payment_method }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $statusColor = match($payment->status) {
                                    'completed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'failed' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                                    default => 'bg-white/10 text-white/60 border-white/20',
                                };
                            @endphp
                            <span class="px-3 py-1.5 rounded-full {{ $statusColor }} text-[10px] font-black uppercase tracking-widest border">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-sm text-white/60 font-medium">{{ $payment->created_at->format('M d, H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-white/5">
            {{ $payments->links() }}
        </div>
    </div>
</x-admin-layout>
