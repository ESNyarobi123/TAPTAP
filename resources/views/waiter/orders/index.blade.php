<x-waiter-layout>
    <x-slot name="header">
        My Service History
    </x-slot>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-white/5">
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Order Details</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-white/40 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($orders as $order)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center font-bold text-violet-400 border border-violet-500/20 group-hover:scale-110 transition-transform">
                                        #{{ $order->table_number }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-white">Order #{{ $order->id }}</p>
                                        <p class="text-[11px] font-medium text-white/40 uppercase tracking-wider">{{ $order->created_at->format('M d, H:i') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex -space-x-2">
                                    @foreach($order->items->take(4) as $item)
                                        <div class="w-10 h-10 rounded-lg border-2 border-surface-900 bg-white/5 flex items-center justify-center text-[10px] font-bold text-white/60 overflow-hidden">
                                            @if($item->menuItem && $item->menuItem->image)
                                                <img src="{{ asset('storage/' . $item->menuItem->image) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($item->name ?? ($item->menuItem ? $item->menuItem->name : 'C'), 0, 1) }}
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 4)
                                        <div class="w-10 h-10 rounded-lg border-2 border-surface-900 bg-gradient-to-br from-violet-600 to-cyan-600 flex items-center justify-center text-[10px] font-bold text-white">
                                            +{{ $order->items->count() - 4 }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-white">Tsh {{ number_format($order->total_amount) }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                    {{ $order->status == 'paid' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/20 text-amber-400 border border-amber-500/20' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-0">
                                <div class="p-6">
                                    <x-empty-state
                                        title="No orders yet"
                                        description="Your service history will appear here once you have orders."
                                        :action-url="route('waiter.dashboard')"
                                        action-label="Go to Dashboard"
                                    />
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-white/5">
            {{ $orders->links() }}
        </div>
    </div>
</x-waiter-layout>
