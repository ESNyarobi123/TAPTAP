<x-admin-layout>
    <x-slot name="header">
        Transaction Details
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
            <div class="flex justify-between items-start mb-10">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Transaction Reference</p>
                    <h2 class="text-2xl font-mono font-black text-slate-900 tracking-tighter uppercase">{{ $payment->transaction_id ?? 'N/A' }}</h2>
                </div>
                @php
                    $statusColor = match($payment->status) {
                        'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                        'failed' => 'bg-red-50 text-red-600 border-red-100',
                        default => 'bg-slate-50 text-slate-600 border-slate-100',
                    };
                @endphp
                <span class="px-6 py-2 rounded-full {{ $statusColor }} text-xs font-black uppercase tracking-widest border">
                    {{ $payment->status }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 py-10 border-t border-slate-50">
                <div class="space-y-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Amount Paid</p>
                        <p class="text-3xl font-black text-slate-900 tracking-tighter">Tsh {{ number_format($payment->amount, 0) }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Payment Method</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-900">
                                <i data-lucide="credit-card" class="w-5 h-5"></i>
                            </div>
                            <p class="text-slate-900 font-bold">{{ strtoupper($payment->payment_method) }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Associated Order</p>
                        <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-slate-900 font-bold hover:text-blue-600 transition-all flex items-center gap-2">
                            Order #{{ str_pad($payment->order_id, 6, '0', STR_PAD_LEFT) }}
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Restaurant</p>
                        <p class="text-slate-900 font-bold">{{ $payment->order->restaurant->name }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-10 border-t border-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Payment Timeline</p>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        <div class="flex-1 flex justify-between items-center">
                            <p class="text-sm font-bold text-slate-900">Payment Completed</p>
                            <p class="text-xs text-slate-400">{{ $payment->updated_at->format('M d, Y • H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-2 h-2 bg-slate-200 rounded-full"></div>
                        <div class="flex-1 flex justify-between items-center">
                            <p class="text-sm font-bold text-slate-500">Transaction Initiated</p>
                            <p class="text-xs text-slate-400">{{ $payment->created_at->format('M d, Y • H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
