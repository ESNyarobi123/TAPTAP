<x-waiter-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Tips Today -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center group-hover:bg-orange-red transition-colors duration-300">
                    <i data-lucide="coins" class="w-7 h-7 text-orange-red group-hover:text-white transition-colors"></i>
                </div>
                <span class="text-[10px] font-black text-green-500 bg-green-50 px-2 py-1 rounded-lg">+12%</span>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Tips Today</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight">Tsh {{ number_format($tipsToday) }}</h3>
        </div>

        <!-- Active Orders -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-300">
                    <i data-lucide="shopping-bag" class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors"></i>
                </div>
                <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">Live</span>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Active Orders</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight">{{ $activeOrders }}</h3>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center group-hover:bg-red-500 transition-colors duration-300">
                    <i data-lucide="bell" class="w-7 h-7 text-red-500 group-hover:text-white transition-colors"></i>
                </div>
                @if($pendingRequests->count() > 0)
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                @endif
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Customer Calls</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight">{{ $pendingRequests->count() }}</h3>
        </div>

        <!-- Weekly Tips -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 transition-colors duration-300">
                    <i data-lucide="trending-up" class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors"></i>
                </div>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">This Week</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight">Tsh {{ number_format($tipsThisWeek) }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Content (Left) -->
        <div class="lg:col-span-2 space-y-12">
            <!-- Customer Requests Section -->
            <section id="requests">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-deep-blue tracking-tight">Urgent Requests</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Customers waiting for attention</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($pendingRequests as $request)
                        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 hover:shadow-2xl transition-all group relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-red/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                            
                            <div class="flex justify-between items-start mb-6 relative z-10">
                                <div class="w-16 h-16 {{ $request->type == 'request_bill' ? 'bg-blue-50' : 'bg-orange-50' }} rounded-[1.5rem] flex items-center justify-center">
                                    <i data-lucide="{{ $request->type == 'request_bill' ? 'receipt' : 'bell-ring' }}" class="w-8 h-8 {{ $request->type == 'request_bill' ? 'text-blue-600' : 'text-orange-red' }} {{ $request->type != 'request_bill' ? 'animate-bounce' : '' }}"></i>
                                </div>
                                <span class="text-[10px] font-black text-gray-300 bg-gray-50 px-3 py-1 rounded-full uppercase">{{ $request->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="relative z-10 mb-8">
                                <h4 class="text-3xl font-black text-deep-blue mb-1">Table #{{ $request->table_number }}</h4>
                                <p class="text-sm font-bold {{ $request->type == 'request_bill' ? 'text-blue-500' : 'text-orange-red' }} uppercase tracking-widest">
                                    {{ $request->type == 'request_bill' ? 'Requesting Bill' : 'Calling Waiter' }}
                                </p>
                            </div>

                            <form action="{{ route('waiter.requests.complete', $request->id) }}" method="POST" class="relative z-10">
                                @csrf
                                <button type="submit" class="w-full bg-deep-blue text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-orange-red transition-all shadow-lg shadow-deep-blue/10 flex items-center justify-center gap-3">
                                    <i data-lucide="check" class="w-4 h-4"></i> Mark as Attended
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-full bg-gray-50/30 border-2 border-dashed border-gray-100 rounded-[3rem] py-20 text-center">
                            <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                                <i data-lucide="coffee" class="w-10 h-10 text-gray-200"></i>
                            </div>
                            <h4 class="text-xl font-black text-gray-300">No pending requests</h4>
                            <p class="text-sm text-gray-400 font-medium">All tables are currently served!</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- My Orders Section -->
            <section>
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-deep-blue tracking-tight">My Orders Today</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Your service history for today</p>
                    </div>
                    <a href="#" class="bg-white px-6 py-3 rounded-xl border border-gray-100 text-xs font-black text-deep-blue hover:border-orange-red transition-all">View All History</a>
                </div>

                <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Table</th>
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Items</th>
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total Amount</th>
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($myOrders as $order)
                                    <tr class="hover:bg-gray-50/30 transition-colors group">
                                        <td class="px-10 py-8">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center font-black text-deep-blue group-hover:bg-deep-blue group-hover:text-white transition-all">
                                                    #{{ $order->table_number }}
                                                </div>
                                                <span class="text-[10px] font-bold text-gray-300">{{ $order->created_at->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-8">
                                            <div class="flex -space-x-3">
                                                @foreach($order->items->take(4) as $item)
                                                    <div class="w-10 h-10 rounded-2xl border-4 border-white bg-gray-100 flex items-center justify-center text-[10px] font-black text-deep-blue overflow-hidden shadow-sm" title="{{ $item->menuItem->name }}">
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
                                            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">No orders taken yet today</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <!-- Sidebar Content (Right) -->
        <div class="space-y-12">
            <!-- Earnings Card -->
            <div class="bg-deep-blue p-10 rounded-[3.5rem] shadow-2xl shadow-deep-blue/40 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-orange-red/20 rounded-full blur-[80px] -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/10 rounded-full blur-[60px] -ml-24 -mb-24"></div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-10">
                        <h3 class="text-xl font-black tracking-tight">Earnings Summary</h3>
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                            <i data-lucide="wallet" class="w-5 h-5 text-blue-300"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-10">
                        <div>
                            <p class="text-[10px] font-black text-blue-300/60 uppercase tracking-[0.2em] mb-2">Today's Tips</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black">Tsh {{ number_format($tipsToday) }}</span>
                                <span class="text-xs font-bold text-green-400">Good job!</span>
                            </div>
                        </div>
                        
                        <div class="h-px bg-white/10"></div>
                        
                        <div>
                            <p class="text-[10px] font-black text-blue-300/60 uppercase tracking-[0.2em] mb-6">Weekly Performance</p>
                            <div class="flex items-end gap-3 h-32">
                                @foreach([30, 50, 40, 80, 60, 90, 70] as $height)
                                    <div class="flex-1 bg-white/10 rounded-t-xl hover:bg-orange-red transition-all cursor-pointer group relative" style="height: {{ $height }}%">
                                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-white text-deep-blue text-[9px] font-black px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-all shadow-xl whitespace-nowrap">
                                            Tsh {{ number_format($height * 200) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between mt-4">
                                <span class="text-[8px] font-black text-blue-400 uppercase tracking-widest">Mon</span>
                                <span class="text-[8px] font-black text-blue-400 uppercase tracking-widest">Sun</span>
                            </div>
                        </div>

                        <div class="bg-white/5 p-6 rounded-[2rem] border border-white/10 flex justify-between items-center">
                            <div>
                                <p class="text-[9px] font-black text-blue-300/60 uppercase tracking-widest mb-1">Total This Week</p>
                                <p class="text-2xl font-black">Tsh {{ number_format($tipsThisWeek) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-red rounded-2xl flex items-center justify-center shadow-lg shadow-orange-red/20">
                                <i data-lucide="arrow-up-right" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ratings Section -->
            <div class="bg-white p-10 rounded-[3.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-deep-blue tracking-tight">Customer Ratings</h3>
                    <div class="flex items-center gap-1 text-yellow-orange">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <span class="text-sm font-black">4.8</span>
                    </div>
                </div>
                
                <div class="space-y-6">
                    @forelse($recentFeedback as $feedback)
                        <div class="p-6 rounded-[2rem] bg-gray-50/50 border border-transparent hover:border-gray-100 transition-all group">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex text-yellow-orange">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="w-3 h-3 {{ $i <= $feedback->rating ? 'fill-current' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-[9px] font-bold text-gray-300 uppercase">{{ $feedback->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 italic font-medium leading-relaxed">"{{ $feedback->comment }}"</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-[9px] font-black text-deep-blue uppercase tracking-widest">Table #{{ $feedback->order->table_number }}</span>
                                <div class="w-6 h-6 bg-white rounded-lg flex items-center justify-center text-gray-300 group-hover:text-orange-red transition-colors">
                                    <i data-lucide="heart" class="w-3 h-3"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="star" class="w-8 h-8 text-gray-200"></i>
                            </div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No ratings yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-waiter-layout>
