<x-admin-layout>
    <x-slot name="header">
        User Details
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
            <div class="flex justify-between items-start mb-10">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-slate-900 rounded-[2rem] flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-slate-900/20">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tighter">{{ $user->name }}</h2>
                        <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-xs mt-1">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="px-6 py-3 bg-slate-50 text-slate-900 rounded-2xl font-bold text-sm hover:bg-slate-900 hover:text-white transition-all flex items-center gap-2">
                        <i data-lucide="edit-3" class="w-4 h-4"></i> Edit User
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 py-10 border-t border-slate-50">
                <div class="space-y-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Account Role</p>
                        @php
                            $role = $user->getRoleNames()->first();
                            $roleColor = match($role) {
                                'super_admin' => 'bg-slate-900 text-white',
                                'manager' => 'bg-blue-50 text-blue-600',
                                'waiter' => 'bg-orange-50 text-orange-600',
                                default => 'bg-slate-50 text-slate-600',
                            };
                        @endphp
                        <span class="px-4 py-1.5 rounded-full {{ $roleColor }} text-[10px] font-black uppercase tracking-widest border border-transparent">
                            {{ str_replace('_', ' ', $role) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Associated Restaurant</p>
                        @if($user->restaurant)
                            <a href="{{ route('admin.restaurants.show', $user->restaurant) }}" class="text-slate-900 font-bold hover:text-blue-600 transition-all flex items-center gap-2">
                                {{ $user->restaurant->name }}
                                <i data-lucide="external-link" class="w-3 h-3"></i>
                            </a>
                        @else
                            <p class="text-slate-900 font-bold">System Wide (No Restaurant)</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Member Since</p>
                        <p class="text-slate-900 font-bold">{{ $user->created_at->format('F d, Y') }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">{{ $user->created_at->diffForHumans() }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Verification</p>
                        @if($user->email_verified_at)
                            <span class="flex items-center gap-2 text-emerald-600 font-bold text-sm">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Verified
                            </span>
                        @else
                            <span class="flex items-center gap-2 text-red-500 font-bold text-sm">
                                <i data-lucide="x-circle" class="w-4 h-4"></i> Unverified
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
