<x-admin-layout>
    <x-slot name="header">
        Payments & Transactions
    </x-slot>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tighter">Transaction History</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Monitor all financial activities across the platform</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Transaction ID</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Order</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Restaurant</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Method</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-8 py-6">
                            <span class="font-mono text-xs text-slate-500 uppercase">{{ $payment->transaction_id ?? 'N/A' }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-bold text-slate-900">#{{ str_pad($payment->order_id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm text-slate-900 font-bold">{{ $payment->order->restaurant->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm text-slate-900 font-black">Tsh {{ number_format($payment->amount, 0) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-full uppercase tracking-widest">{{ $payment->payment_method }}</span>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $statusColor = match($payment->status) {
                                    'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                    'failed' => 'bg-red-50 text-red-600 border-red-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100',
                                };
                            @endphp
                            <span class="px-4 py-1.5 rounded-full {{ $statusColor }} text-[10px] font-black uppercase tracking-widest border">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500 font-medium">{{ $payment->created_at->format('M d, H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-8 border-t border-slate-50">
            {{ $payments->links() }}
        </div>
    </div>
</x-admin-layout>
