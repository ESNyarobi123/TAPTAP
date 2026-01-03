<x-admin-layout>
    <x-slot name="header">
        Edit User
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
            <div class="mb-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter">User Permissions</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Update user profile and access levels</p>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all" required>
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 ml-4">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all" required>
                        @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 ml-4">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">System Role</label>
                        <select name="role" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-red-500 text-[10px] font-bold mt-1 ml-4">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Assigned Restaurant</label>
                        <select name="restaurant_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                            <option value="">None (System Admin)</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}" {{ $user->restaurant_id == $restaurant->id ? 'selected' : '' }}>
                                    {{ $restaurant->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('restaurant_id') <p class="text-red-500 text-[10px] font-bold mt-1 ml-4">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-8">
                    <a href="{{ route('admin.users.index') }}" class="px-8 py-4 bg-slate-50 text-slate-400 rounded-2xl font-bold text-sm hover:bg-slate-100 transition-all">Cancel</a>
                    <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-2xl hover:shadow-slate-900/20 transition-all">Update User</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
