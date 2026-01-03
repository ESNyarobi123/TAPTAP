<x-admin-layout>
    <x-slot name="header">
        Order Details
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Info -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
                            <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px] mt-1">Placed on {{ $order->created_at->format('M d, Y • H:i') }}</p>
                        </div>
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
                        <span class="px-6 py-2 rounded-full {{ $statusColor }} text-xs font-black uppercase tracking-widest border">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest border-b border-slate-50 pb-4">Order Items</h3>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center font-black text-slate-400 shadow-sm">
                                        {{ $item->quantity }}x
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $item->menuItem->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Tsh {{ number_format($item->price, 0) }} per unit</p>
                                    </div>
                                </div>
                                <p class="font-black text-slate-900">Tsh {{ number_format($item->price * $item->quantity, 0) }}</p>
                            </div>
                            @endforeach
                        </div>

                        <div class="pt-6 border-t border-slate-50 space-y-2">
                            <div class="flex justify-between items-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                                <span>Subtotal</span>
                                <span>Tsh {{ number_format($order->total_amount, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-900 font-black text-xl tracking-tighter pt-2">
                                <span>Total Amount</span>
                                <span>Tsh {{ number_format($order->total_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restaurant & Waiter -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Restaurant</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-black">
                                {{ substr($order->restaurant->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $order->restaurant->name }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $order->restaurant->location }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Assigned Waiter</p>
                        @if($order->waiter)
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center font-black">
                                {{ substr($order->waiter->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $order->waiter->name }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">Staff ID: #{{ $order->waiter->id }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-sm font-bold text-slate-400 italic">No waiter assigned</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-slate-900/20">
                    <h3 class="text-xl font-black tracking-tighter mb-6">Payment Status</h3>
                    @if($order->payment)
                    <div class="space-y-6">
                        <div class="p-6 bg-white/10 rounded-[2rem] border border-white/10">
                            <p class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1">Transaction ID</p>
                            <p class="font-mono text-xs truncate">{{ $order->payment->transaction_id }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black text-white/50 uppercase tracking-widest">Method</p>
                                <p class="font-bold">{{ strtoupper($order->payment->payment_method) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-white/50 uppercase tracking-widest">Status</p>
                                <span class="px-3 py-1 bg-emerald-500 text-white text-[9px] font-black rounded-full uppercase tracking-widest">{{ $order->payment->status }}</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center py-8 text-center bg-white/5 rounded-[2rem]">
                        <i data-lucide="credit-card" class="w-10 h-10 text-white/20 mb-4"></i>
                        <p class="text-sm font-bold">No Payment Found</p>
                        <p class="text-[10px] text-white/40 font-medium uppercase tracking-widest mt-1">Awaiting customer action</p>
                    </div>
                    @endif
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-6">Actions</h3>
                    <div class="space-y-3">
                        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all mb-4">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Mark as Pending</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Mark as Preparing</option>
                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Mark as Ready</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Mark as Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Mark as Cancelled</option>
                            </select>
                        </form>
                        <button class="w-full py-4 bg-slate-50 text-slate-900 rounded-2xl font-bold text-sm hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center gap-2">
                            <i data-lucide="printer" class="w-4 h-4"></i> Print Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
