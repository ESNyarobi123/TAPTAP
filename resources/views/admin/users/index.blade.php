<x-admin-layout>
    <x-slot name="header">User Management</x-slot>

    @include('admin.partials.page-styles')
    @include('admin.partials.flash')

    @include('admin.partials.page-hero', [
        'eyebrow' => 'People',
        'title' => 'Users & Roles',
        'subtitle' => 'Manage administrators, managers, and waiters across the platform.',
        'accent' => 'indigo',
    ])

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        @include('admin.partials.stat-chip', ['label' => 'Total users', 'value' => number_format($users->total()), 'tone' => 'violet'])
        @include('admin.partials.stat-chip', ['label' => 'This page', 'value' => $users->count(), 'tone' => 'cyan'])
        @include('admin.partials.stat-chip', ['label' => 'Filter role', 'value' => request('role') ? str_replace('_', ' ', ucfirst(request('role'))) : 'All', 'tone' => 'white'])
        @include('admin.partials.stat-chip', ['label' => 'Search', 'value' => request('q') ?: '—', 'tone' => 'amber'])
    </div>

    <div class="glass-card admin-data-panel rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-white/5">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="relative flex-1 min-w-[200px] max-w-xs">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Name or email..."
                           class="w-full pl-10 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-[38px] text-white/40"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <div class="min-w-[160px]">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Role</label>
                    <select name="role" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-violet-500 [&>option]:bg-gray-900">
                        <option value="">All roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($role->name)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 hover:from-violet-500 hover:to-cyan-500 text-white rounded-xl font-semibold text-sm shadow-lg shadow-violet-500/20 transition-all">Filter</button>
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/15 text-white rounded-xl font-semibold text-sm border border-white/10 transition-all">Clear</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[640px]">
                <thead>
                    <tr class="bg-white/5 admin-table-head">
                        <th>User</th>
                        <th>Role</th>
                        <th>Restaurant</th>
                        <th>Joined</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                    <tr class="admin-table-row group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center text-violet-400 font-black text-sm border border-violet-500/20 group-hover:scale-105 transition-transform shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-white leading-none mb-0.5">{{ $user->name }}</p>
                                    <p class="text-[10px] text-white/40 font-bold">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $role = $user->getRoleNames()->first();
                                $roleColor = match($role) {
                                    'super_admin' => 'bg-gradient-to-r from-violet-600/80 to-cyan-600/80 text-white border-0',
                                    'manager' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'waiter' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                                    default => 'bg-white/10 text-white/60 border-white/20',
                                };
                            @endphp
                            <span class="px-3 py-1 {{ $roleColor }} text-[10px] font-black rounded-full uppercase tracking-widest border">{{ str_replace('_', ' ', $role ?? '—') }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @if($user->restaurant)
                                <a href="{{ route('admin.restaurants.show', $user->restaurant) }}" class="text-xs font-bold text-white/70 hover:text-violet-400 transition-colors">{{ $user->restaurant->name }}</a>
                            @else
                                <span class="text-xs font-bold text-white/40">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-medium text-white/40">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 glass text-white/40 hover:bg-violet-600 hover:text-white rounded-xl transition-all" title="View"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 glass text-white/40 hover:bg-violet-600 hover:text-white rounded-xl transition-all" title="Edit"><i data-lucide="edit-3" class="w-4 h-4"></i></a>
                                @if(Auth::id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 glass text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl transition-all" title="Delete"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white/30"><i data-lucide="users" class="w-8 h-8"></i></div>
                                <p class="text-white font-bold">No users found</p>
                                <p class="text-sm text-white/50">Try a different search or role filter.</p>
                                <a href="{{ route('admin.users.index') }}" class="text-violet-400 hover:text-violet-300 text-sm font-semibold">Clear filters</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-6 border-t border-white/5">{{ $users->links() }}</div>
        @endif
    </div>
</x-admin-layout>
