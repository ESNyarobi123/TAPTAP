<x-manager-layout>
    <x-slot name="header">
        Manager Dashboard
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Stat 1: Orders -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center group-hover:bg-orange-red transition-colors duration-300">
                    <i data-lucide="shopping-cart" class="w-7 h-7 text-orange-red group-hover:text-white transition-colors"></i>
                </div>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Total Orders Today</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight">{{ number_format($totalOrdersToday) }}</h3>
        </div>

        <!-- Stat 2: Revenue -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-deep-blue transition-colors duration-300">
                    <i data-lucide="dollar-sign" class="w-7 h-7 text-deep-blue group-hover:text-white transition-colors"></i>
                </div>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Revenue Today</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight">Tsh {{ number_format($revenueToday) }}</h3>
        </div>

        <!-- Stat 3: Rating -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center group-hover:bg-yellow-orange transition-colors duration-300">
                    <i data-lucide="star" class="w-7 h-7 text-yellow-orange group-hover:text-white transition-colors"></i>
                </div>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Avg. Rating</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight">{{ number_format($avgRating, 1) }}/5.0</h3>
        </div>

        <!-- Stat 4: Waiters -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 transition-colors duration-300">
                    <i data-lucide="users" class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors"></i>
                </div>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Waiters Online</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight">{{ $waitersOnline }} Active</h3>
        </div>
    </div>

    <!-- Smart Live Order Tracking -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-black text-deep-blue tracking-tight">Live Order Tracking</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Real-time kitchen & service status</p>
            </div>
            <div class="flex gap-4">
                <button onclick="window.location.reload()" class="bg-white border border-gray-100 px-6 py-3 rounded-2xl font-bold text-gray-600 hover:border-orange-red transition-all flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh
                </button>
                <a href="{{ route('manager.orders.live') }}" class="bg-deep-blue text-white px-6 py-3 rounded-2xl font-bold hover:bg-orange-red transition-all flex items-center gap-2 shadow-lg shadow-deep-blue/20">
                    <i data-lucide="maximize" class="w-4 h-4"></i> Full Screen
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Pending Column -->
            <div class="bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100">
                <div class="flex items-center justify-between mb-6 px-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-red rounded-full animate-pulse"></div>
                        <h4 class="font-black text-deep-blue uppercase tracking-widest text-[10px]">Pending</h4>
                    </div>
                    <span class="bg-orange-red/10 text-orange-red text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingOrders->count() }}</span>
                </div>
                <div class="space-y-4">
                    @forelse($pendingOrders->take(3) as $order)
                        <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-black text-deep-blue">Table #{{ $order->table_number }}</span>
                                <span class="text-[9px] font-bold text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex -space-x-2 mb-4">
                                @foreach($order->items->take(3) as $item)
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-[10px] font-black text-deep-blue overflow-hidden" title="{{ $item->menuItem->name }}">
                                        @if($item->menuItem->image)
                                            <img src="{{ asset('storage/' . $item->menuItem->image) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($item->menuItem->name, 0, 1) }}
                                        @endif
                                    </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-50 flex items-center justify-center text-[8px] font-black text-gray-400">
                                        +{{ $order->items->count() - 3 }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-orange-red">Tsh {{ number_format($order->total_amount) }}</span>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300"></i>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <i data-lucide="check-circle-2" class="w-8 h-8 text-gray-200 mx-auto mb-2"></i>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">All Clear</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Preparing Column -->
            <div class="bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100">
                <div class="flex items-center justify-between mb-6 px-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-yellow-orange rounded-full animate-pulse"></div>
                        <h4 class="font-black text-deep-blue uppercase tracking-widest text-[10px]">Preparing</h4>
                    </div>
                    <span class="bg-yellow-orange/10 text-yellow-orange text-[10px] font-black px-2 py-0.5 rounded-full">{{ $preparingOrders->count() }}</span>
                </div>
                <div class="space-y-4">
                    @forelse($preparingOrders->take(3) as $order)
                        <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-black text-deep-blue">Table #{{ $order->table_number }}</span>
                                <div class="flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3 text-yellow-orange"></i>
                                    <span class="text-[9px] font-bold text-yellow-orange">In Kitchen</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5 rounded-full mb-4 overflow-hidden">
                                <div class="bg-yellow-orange h-full animate-pulse" style="width: 65%"></div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-deep-blue">{{ $order->items->count() }} Items</span>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300"></i>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <i data-lucide="utensils" class="w-8 h-8 text-gray-200 mx-auto mb-2"></i>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kitchen Empty</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Served Column -->
            <div class="bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100">
                <div class="flex items-center justify-between mb-6 px-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <h4 class="font-black text-deep-blue uppercase tracking-widest text-[10px]">Served</h4>
                    </div>
                    <span class="bg-green-500/10 text-green-500 text-[10px] font-black px-2 py-0.5 rounded-full">{{ $servedOrders->count() }}</span>
                </div>
                <div class="space-y-4">
                    @forelse($servedOrders->take(3) as $order)
                        <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-black text-deep-blue">Table #{{ $order->table_number }}</span>
                                <span class="bg-green-50 text-green-500 text-[8px] font-black px-2 py-0.5 rounded-full uppercase">Ready to Pay</span>
                            </div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center">
                                    <i data-lucide="credit-card" class="w-4 h-4 text-purple-600"></i>
                                </div>
                                <p class="text-[10px] font-bold text-gray-400">Waiting for payment</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-deep-blue">Tsh {{ number_format($order->total_amount) }}</span>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300"></i>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <i data-lucide="smile" class="w-8 h-8 text-gray-200 mx-auto mb-2"></i>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No Served Orders</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Paid Column -->
            <div class="bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100">
                <div class="flex items-center justify-between mb-6 px-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <h4 class="font-black text-deep-blue uppercase tracking-widest text-[10px]">Completed</h4>
                    </div>
                    <span class="bg-blue-500/10 text-blue-500 text-[10px] font-black px-2 py-0.5 rounded-full">{{ $paidOrders->count() }}</span>
                </div>
                <div class="space-y-4">
                    @forelse($paidOrders->take(3) as $order)
                        <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 opacity-60">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-black text-deep-blue">Table #{{ $order->table_number }}</span>
                                <i data-lucide="check-circle-2" class="w-3 h-3 text-green-500"></i>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400">Completed {{ $order->updated_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <i data-lucide="history" class="w-8 h-8 text-gray-200 mx-auto mb-2"></i>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No History</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Feedback & Messages -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Customer Feedback -->
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-deep-blue tracking-tight">Recent Feedback</h3>
                <a href="{{ route('manager.feedback.index') }}" class="text-xs font-bold text-orange-red hover:underline">View All</a>
            </div>
            <div class="space-y-6">
                @forelse($recentFeedback as $feedback)
                    <div class="flex gap-4 p-4 rounded-2xl hover:bg-gray-50 transition-all">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center font-black text-orange-red">
                            {{ substr($feedback->order->customer_name ?? 'C', 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <h5 class="font-bold text-deep-blue">{{ $feedback->order->customer_name ?? 'Customer' }}</h5>
                                <div class="flex text-yellow-orange">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="w-3 h-3 {{ $i <= $feedback->rating ? 'fill-current' : '' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 italic">"{{ $feedback->comment }}"</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-8">No feedback yet</p>
                @endforelse
            </div>
        </div>

        <!-- Waiter Performance / Tips -->
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-deep-blue tracking-tight">Waiter Tips Today</h3>
                <a href="{{ route('manager.tips.index') }}" class="text-xs font-bold text-orange-red hover:underline">View All</a>
            </div>
            <div class="space-y-6">
                @forelse($waiterTips as $tip)
                    <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-gray-50 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center font-black text-purple-600">
                                {{ substr($tip->waiter->name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="font-bold text-deep-blue">{{ $tip->waiter->name }}</h5>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Active Waiter</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-deep-blue">Tsh {{ number_format($tip->total_amount) }}</p>
                            @if($loop->first)
                                <p class="text-[10px] font-bold text-green-500 uppercase tracking-widest">Top Earner</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-8">No tips recorded today</p>
                @endforelse
            </div>
        </div>
    </div>
</x-manager-layout>
