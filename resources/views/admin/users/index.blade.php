<x-admin-layout>
    <x-slot name="header">
        User Management
    </x-slot>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 card-shadow overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tighter">System Users</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Manage administrators, managers, and waiters</p>
            </div>
            <div class="flex gap-4 w-full md:w-auto">
                <div class="relative flex-1 md:w-64">
                    <input type="text" placeholder="Search users..." class="w-full pl-12 pr-6 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                </div>
                <button class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-lg transition-all flex items-center gap-2 shrink-0">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Add User
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">User</th>
                        <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Role</th>
                        <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Restaurant</th>
                        <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Joined</th>
                        <th class="px-8 py-4 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/30 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 font-black text-sm border border-slate-100 group-hover:scale-110 transition-transform">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 leading-none mb-1">{{ $user->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $role = $user->getRoleNames()->first();
                                $roleColor = match($role) {
                                    'super_admin' => 'bg-slate-900 text-white',
                                    'manager' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'waiter' => 'bg-orange-50 text-orange-600 border-orange-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100',
                                };
                            @endphp
                            <span class="px-3 py-1 {{ $roleColor }} text-[9px] font-black rounded-full uppercase tracking-widest border">
                                {{ str_replace('_', ' ', $role) }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-bold text-slate-500">{{ $user->restaurant->name ?? 'System' }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-medium text-slate-400">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition-all" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition-all" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                @if(Auth::id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all" title="Delete">
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
        <div class="p-8 border-t border-slate-50">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
