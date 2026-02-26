@php
    $badgeClass = match($status) {
        'pending' => 'bg-rose-500/20 text-rose-400 border-rose-500/20',
        'preparing' => 'bg-amber-500/20 text-amber-400 border-amber-500/20',
        'served' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/20',
        default => 'bg-rose-500/20 text-rose-400 border-rose-500/20',
    };
@endphp
<div class="glass p-4 rounded-xl card-hover group">
    <div class="flex justify-between items-start mb-3">
        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border {{ $badgeClass }}">Table #{{ $order->table_number }}</span>
        <span class="text-[10px] font-medium text-white/40">{{ $order->created_at->diffForHumans() }}</span>
    </div>
    <div class="space-y-1.5 mb-4">
        @foreach($order->items as $item)
            <div class="flex justify-between items-center gap-2 text-sm">
                <div class="flex items-center gap-2 min-w-0">
                    @if($item->menuItem && $item->menuItem->image)
                        <img src="{{ $item->menuItem->imageUrl() }}" alt="" class="w-8 h-8 rounded object-cover shrink-0 border border-white/10">
                    @endif
                    <span class="font-semibold text-white truncate">{{ $item->quantity }}x {{ $item->name ?? ($item->menuItem?->name ?? 'Item') }}</span>
                </div>
                <span class="text-white/40 shrink-0">Tsh {{ number_format($item->total) }}</span>
            </div>
        @endforeach
    </div>
    <div class="flex items-center justify-between pt-3 border-t border-white/5">
        <span class="font-bold text-white">Tsh {{ number_format($order->total_amount) }}</span>
        <div class="flex gap-2 flex-wrap">
            <form action="{{ route('order-portal.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-lg hover:bg-white/10 text-white/40 hover:text-rose-400 transition-all" title="Delete">🗑</button>
            </form>
            <button type="button" onclick="openEditOrderModal({{ $order->id }}, {{ json_encode($order->table_number) }}, {{ json_encode($order->customer_phone ?? '') }}, {{ json_encode($order->customer_name ?? '') }})" class="p-2 rounded-lg hover:bg-white/10 text-white/40 hover:text-violet-400 transition-all" title="Edit details">✏️</button>
            <button type="button" onclick="openEditItemsModal({{ $order->id }}, {{ json_encode($order->items->map(fn($i) => ['id' => $i->menu_item_id, 'quantity' => $i->quantity])->values()->all()) }})" class="p-2 rounded-lg hover:bg-white/10 text-white/40 hover:text-cyan-400 transition-all" title="Edit menu items">📋</button>
            @if($status === 'pending')
                <form action="{{ route('order-portal.orders.update', $order) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit" class="bg-gradient-to-r from-violet-600 to-cyan-600 text-white p-2 rounded-lg hover:shadow-lg transition-all" title="Start Preparing">▶</button>
                </form>
            @endif
            @if($status === 'preparing')
                <form action="{{ route('order-portal.orders.update', $order) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="served">
                    <button type="submit" class="bg-emerald-500 text-white p-2 rounded-lg hover:bg-emerald-600 transition-all" title="Mark Served">✓</button>
                </form>
            @endif
            @if($status === 'served')
                <button type="button" onclick="openPaymentModal({{ $order->id }}, {{ $order->total_amount }})" class="flex-1 min-w-0 bg-gradient-to-r from-violet-600 to-cyan-600 text-white py-2 px-3 rounded-lg text-xs font-semibold hover:shadow-lg transition-all">Pay</button>
            @endif
        </div>
    </div>
</div>
