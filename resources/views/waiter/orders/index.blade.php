<x-waiter-layout>
    <x-slot name="header">
        My Service History
    </x-slot>

    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Order Details</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Items</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center font-black text-deep-blue">
                                    #{{ $order->table_number }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-deep-blue">Order #{{ $order->id }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $order->created_at->format('M d, H:i') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex -space-x-3">
                                @foreach($order->items->take(4) as $item)
                                    <div class="w-10 h-10 rounded-2xl border-4 border-white bg-gray-100 flex items-center justify-center text-[10px] font-black text-deep-blue overflow-hidden shadow-sm">
                                        @if($item->menuItem->image)
                                            <img src="{{ asset('storage/' . $item->menuItem->image) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($item->menuItem->name, 0, 1) }}
                                        @endif
                                    </div>
                                @endforeach
                                @if($order->items->count() > 4)
                                    <div class="w-10 h-10 rounded-2xl border-4 border-white bg-deep-blue flex items-center justify-center text-[10px] font-black text-white shadow-sm">
                                        +{{ $order->items->count() - 4 }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            <p class="font-black text-deep-blue">Tsh {{ number_format($order->total_amount) }}</p>
                        </td>
                        <td class="px-10 py-8">
                            <span class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest 
                                {{ $order->status == 'paid' ? 'bg-green-50 text-green-500' : 'bg-orange-50 text-orange-red' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-10 py-20 text-center">
                            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">No orders found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-10 py-6 border-t border-gray-50">
            {{ $orders->links() }}
        </div>
    </div>
</x-waiter-layout>
