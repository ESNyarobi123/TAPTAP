<x-admin-layout>
    <x-slot name="header">
        Restaurant Partners
    </x-slot>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-black text-white tracking-tight">All Restaurants</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mt-1">Manage and monitor your restaurant network</p>
            </div>
            <div class="flex gap-4 w-full md:w-auto">
                <div class="relative flex-1 md:w-64">
                    <input type="text" placeholder="Search restaurants..." class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                    <i data-lucide="search" class="w-4 h-4 text-white/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <button class="px-6 py-3 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-violet-500/25 transition-all flex items-center gap-2 shrink-0">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add New
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5">
                        <th class="px-6 py-4 text-left text-[9px] font-black text-white/40 uppercase tracking-widest">Restaurant</th>
                        <th class="px-6 py-4 text-left text-[9px] font-black text-white/40 uppercase tracking-widest">Location</th>
                        <th class="px-6 py-4 text-left text-[9px] font-black text-white/40 uppercase tracking-widest">Staff</th>
                        <th class="px-6 py-4 text-left text-[9px] font-black text-white/40 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-[9px] font-black text-white/40 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($restaurants as $restaurant)
                    <tr class="hover:bg-white/5 transition-all group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center text-violet-400 font-black text-sm border border-violet-500/20 group-hover:scale-110 transition-transform">
                                    {{ substr($restaurant->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-white leading-none mb-1">{{ $restaurant->name }}</p>
                                    <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest">ID: #{{ str_pad($restaurant->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-white/60">{{ $restaurant->location }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-white">{{ $restaurant->users_count ?? 0 }}</span>
                                    <span class="text-[8px] font-bold text-white/40 uppercase tracking-widest">Total Staff</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($restaurant->is_active)
                                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-[9px] font-black rounded-full uppercase tracking-widest border border-emerald-500/30 flex items-center gap-1.5 w-fit">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span> Active
                                </span>
                            @else
                                <span class="px-3 py-1 bg-rose-500/20 text-rose-400 text-[9px] font-black rounded-full uppercase tracking-widest border border-rose-500/30">Blocked</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="p-2 glass text-white/40 hover:bg-violet-600 hover:text-white rounded-xl transition-all" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="p-2 glass text-white/40 hover:bg-violet-600 hover:text-white rounded-xl transition-all" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.restaurants.toggle-status', $restaurant) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 {{ $restaurant->is_active ? 'glass text-rose-400 hover:bg-rose-500' : 'glass text-emerald-400 hover:bg-emerald-500' }} hover:text-white rounded-xl transition-all" title="{{ $restaurant->is_active ? 'Block' : 'Unblock' }}">
                                        <i data-lucide="{{ $restaurant->is_active ? 'slash' : 'check' }}" class="w-4 h-4"></i>
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
            {{ $restaurants->links() }}
        </div>
    </div>
</x-admin-layout>
