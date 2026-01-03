<x-admin-layout>
    <x-slot name="header">
        Order Management
    </x-slot>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tighter">All System Orders</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Monitor and manage orders across all restaurants</p>
            </div>
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" placeholder="Search orders..." class="pl-12 pr-6 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all w-64">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Order ID</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Restaurant</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($orders as $order)
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-8 py-6">
                            <span class="font-black text-slate-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm text-slate-900 font-bold">{{ $order->restaurant->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm text-slate-900 font-black">Tsh {{ number_format($order->total_amount, 0) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $statusColor = match($order->status) {
                                    'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                    'preparing' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'ready' => 'bg-purple-50 text-purple-600 border-purple-100',
                                    'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100',
                                };
                            @endphp
                            <span class="px-4 py-1.5 rounded-full {{ $statusColor }} text-[10px] font-black uppercase tracking-widest border">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500 font-medium">{{ $order->created_at->format('M d, H:i') }}</td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="p-2 bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition-all" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this order?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all" title="Delete">
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
        <div class="p-8 border-t border-slate-50">
            {{ $orders->links() }}
        </div>
    </div>
</x-admin-layout>
