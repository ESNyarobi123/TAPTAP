<x-manager-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-3xl font-black text-deep-blue tracking-tight">Tips Tracking</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Monitor and distribute staff tips</p>
        </div>
        <div class="flex gap-4">
            <button class="bg-white border border-gray-100 px-6 py-4 rounded-2xl font-bold text-gray-600 hover:border-orange-red transition-all flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5"></i> This Month
            </button>
            <button class="bg-deep-blue text-white px-8 py-4 rounded-2xl font-black text-lg shadow-xl shadow-deep-blue/30 hover:bg-orange-red transition-all flex items-center gap-3">
                <i data-lucide="send" class="w-6 h-6"></i> Distribute Tips
            </button>
        </div>
    </div>

    <!-- Tips Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 group hover:bg-deep-blue transition-all duration-500">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white/10 transition-colors">
                <i data-lucide="coins" class="w-7 h-7 text-orange-red group-hover:text-white"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-gray-300">Total Tips Today</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight group-hover:text-white">Tsh {{ number_format($totalTipsToday) }}</h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 group hover:bg-orange-red transition-all duration-500">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white/10 transition-colors">
                <i data-lucide="trending-up" class="w-7 h-7 text-deep-blue group-hover:text-white"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-orange-100">Avg. Tip per Order</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight group-hover:text-white">Tsh {{ number_format($avgTip) }}</h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 group hover:bg-yellow-orange transition-all duration-500">
            <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white/10 transition-colors">
                <i data-lucide="award" class="w-7 h-7 text-green-600 group-hover:text-white"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-yellow-50">Top Waiter Today</p>
            <h3 class="text-3xl font-black text-deep-blue tracking-tight group-hover:text-white">{{ $topWaiter->name ?? 'None' }}</h3>
        </div>
    </div>

    <!-- Waiter Performance Table -->
    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-xl font-black text-deep-blue tracking-tight">Staff Tip Performance</h3>
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Search staff..." class="pl-12 pr-6 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-orange-red transition-all">
            </div>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Staff Member</th>
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Orders</th>
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Tips</th>
                    <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($waiterPerformance as $waiter)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center font-black text-purple-600">
                                    {{ substr($waiter->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-deep-blue">{{ $waiter->name }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-6 font-bold text-gray-600">{{ $waiter->orders_count }} Orders</td>
                        <td class="px-10 py-6 font-black text-deep-blue">Tsh {{ number_format($waiter->tips_sum_amount ?? 0) }}</td>
                        <td class="px-10 py-6">
                            <button class="text-xs font-black uppercase tracking-widest text-orange-red hover:text-deep-blue transition-colors">View Details</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-10 py-12 text-center text-gray-400 font-bold">No staff data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-manager-layout>
