<x-admin-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Restaurants -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 card-shadow relative overflow-hidden group hover:border-blue-200 transition-all stat-card-blue">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100 group-hover:scale-110 transition-transform">
                        <i data-lucide="utensils" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[9px] font-black rounded-full uppercase tracking-widest">Growth</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Restaurants</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $stats['total_restaurants'] }}</h3>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 card-shadow relative overflow-hidden group hover:border-emerald-200 transition-all stat-card-emerald">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center border border-emerald-100 group-hover:scale-110 transition-transform">
                        <i data-lucide="shopping-bag" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[9px] font-black rounded-full uppercase tracking-widest">Live</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Orders</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $stats['active_orders'] }}</h3>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 card-shadow relative overflow-hidden group hover:border-purple-200 transition-all stat-card-purple">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center border border-purple-100 group-hover:scale-110 transition-transform">
                        <i data-lucide="banknote" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <span class="px-3 py-1 bg-purple-100 text-purple-600 text-[9px] font-black rounded-full uppercase tracking-widest">Revenue</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Revenue</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">Tsh {{ number_format($stats['total_revenue'] / 1000, 1) }}K</h3>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 card-shadow relative overflow-hidden group hover:border-orange-200 transition-all stat-card-orange">
            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center border border-orange-100 group-hover:scale-110 transition-transform">
                        <i data-lucide="wallet" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <span class="px-3 py-1 bg-orange-100 text-orange-600 text-[9px] font-black rounded-full uppercase tracking-widest">Pending</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Withdrawals</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $stats['pending_withdrawals'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Restaurants Table -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 card-shadow overflow-hidden group">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-gradient-to-r from-white to-slate-50">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tighter">Newest Partners</h3>
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1">Recently registered restaurants</p>
                </div>
                <a href="{{ route('admin.restaurants.index') }}" class="px-4 py-2 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-600 hover:text-white transition-all">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Restaurant</th>
                            <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Location</th>
                            <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-4 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recent_restaurants as $restaurant)
                        <tr class="hover:bg-blue-50/30 transition-all group/row">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl flex items-center justify-center text-blue-600 font-black text-sm border border-slate-100 group-hover/row:scale-110 transition-transform">
                                        {{ substr($restaurant->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-900">{{ $restaurant->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-blue-400"></i>
                                    {{ $restaurant->location }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black rounded-full uppercase tracking-widest border border-emerald-100">
                                    {{ $restaurant->is_active ? 'Active' : 'Blocked' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white transition-all">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 card-shadow p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 opacity-20"></div>
            <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-8 relative z-10">Recent Activity</h3>
            <div class="space-y-8 relative z-10">
                @forelse($recent_activities as $activity)
                <div class="flex gap-4 relative group/act">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0 border border-blue-100 group-hover/act:bg-blue-600 transition-all">
                        <i data-lucide="activity" class="w-5 h-5 text-blue-600 group-hover/act:text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 leading-tight mb-1 group-hover/act:text-blue-600 transition-colors">{{ $activity->description }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4 border-4 border-white shadow-inner">
                        <i data-lucide="activity" class="w-10 h-10"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-900">No recent activity</p>
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mt-1">System is quiet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
