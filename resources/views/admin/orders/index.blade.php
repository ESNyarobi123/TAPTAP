<x-admin-layout>
    <x-slot name="header">
        Order Management
    </x-slot>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-white/5 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-white tracking-tight">All System Orders</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mt-1">Monitor and manage orders across all restaurants</p>
            </div>
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" placeholder="Search orders..." class="pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all w-64">
                    <i data-lucide="search" class="w-4 h-4 text-white/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Order ID</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Restaurant</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-white/40 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($orders as $order)
                    <tr class="hover:bg-white/5 transition-all">
                        <td class="px-6 py-5">
                            <span class="font-black text-white">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-white font-bold">{{ $order->restaurant->name }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-white font-black">Tsh {{ number_format($order->total_amount, 0) }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $statusColor = match($order->status) {
                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'preparing' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'ready' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                                    'completed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                    'cancelled' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                                    default => 'bg-white/10 text-white/60 border-white/20',
                                };
                            @endphp
                            <span class="px-3 py-1.5 rounded-full {{ $statusColor }} text-[10px] font-black uppercase tracking-widest border">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-sm text-white/60 font-medium">{{ $order->created_at->format('M d, H:i') }}</td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="p-2 glass text-white/40 hover:bg-violet-600 hover:text-white rounded-xl transition-all" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this order?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 glass text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl transition-all" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-white/5">
            {{ $orders->links() }}
        </div>
    </div>
</x-admin-layout>
