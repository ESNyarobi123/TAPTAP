<x-admin-layout>
    <x-slot name="header">
        User Management
    </x-slot>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-black text-white tracking-tight">System Users</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mt-1">Manage administrators, managers, and waiters</p>
            </div>
            <div class="flex gap-4 w-full md:w-auto">
                <div class="relative flex-1 md:w-64">
                    <input type="text" placeholder="Search users..." class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                    <i data-lucide="search" class="w-4 h-4 text-white/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <button class="px-6 py-3 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-violet-500/25 transition-all flex items-center gap-2 shrink-0">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Add User
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5">
                        <th class="px-6 py-4 text-left text-[9px] font-black text-white/40 uppercase tracking-widest">User</th>
                        <th class="px-6 py-4 text-left text-[9px] font-black text-white/40 uppercase tracking-widest">Role</th>
                        <th class="px-6 py-4 text-left text-[9px] font-black text-white/40 uppercase tracking-widest">Restaurant</th>
                        <th class="px-6 py-4 text-left text-[9px] font-black text-white/40 uppercase tracking-widest">Joined</th>
                        <th class="px-6 py-4 text-right text-[9px] font-black text-white/40 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($users as $user)
                    <tr class="hover:bg-white/5 transition-all group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center text-violet-400 font-black text-sm border border-violet-500/20 group-hover:scale-110 transition-transform">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-white leading-none mb-1">{{ $user->name }}</p>
                                    <p class="text-[10px] text-white/40 font-bold">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $role = $user->getRoleNames()->first();
                                $roleColor = match($role) {
                                    'super_admin' => 'bg-gradient-to-r from-violet-600 to-cyan-600 text-white',
                                    'manager' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'waiter' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                                    default => 'bg-white/10 text-white/60 border-white/20',
                                };
                            @endphp
                            <span class="px-3 py-1 {{ $roleColor }} text-[9px] font-black rounded-full uppercase tracking-widest border">
                                {{ str_replace('_', ' ', $role) }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-white/60">{{ $user->restaurant->name ?? 'System' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-medium text-white/40">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 glass text-white/40 hover:bg-violet-600 hover:text-white rounded-xl transition-all" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 glass text-white/40 hover:bg-violet-600 hover:text-white rounded-xl transition-all" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                @if(Auth::id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 glass text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl transition-all" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-white/5">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
