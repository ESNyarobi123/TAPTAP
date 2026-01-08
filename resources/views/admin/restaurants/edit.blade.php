<x-admin-layout>
    <x-slot name="header">
        Edit Restaurant
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card rounded-2xl p-8">
            <div class="mb-8">
                <h3 class="text-2xl font-black text-white tracking-tight">Restaurant Settings</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mt-1">Update restaurant profile and configuration</p>
            </div>

            <form action="{{ route('admin.restaurants.update', $restaurant) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 block">Restaurant Name</label>
                        <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all" required>
                        @error('name') <p class="text-rose-400 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 block">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $restaurant->phone) }}" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                        @error('phone') <p class="text-rose-400 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 block">Location / Address</label>
                    <input type="text" name="location" value="{{ old('location', $restaurant->location) }}" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                    @error('location') <p class="text-rose-400 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6 border-t border-white/10">
                    <div class="mb-6">
                        <h4 class="text-sm font-black text-white uppercase tracking-widest">Payment Gateway (ZenoPay)</h4>
                        <p class="text-[10px] text-white/40 font-bold mt-1">Configure the API key for processing payments</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 block">ZenoPay API Key</label>
                        <input type="text" name="zenopay_api_key" value="{{ old('zenopay_api_key', $restaurant->zenopay_api_key) }}" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-mono text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all" placeholder="Enter API Key">
                        @error('zenopay_api_key') <p class="text-rose-400 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-6">
                    <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="px-8 py-4 glass text-white/60 rounded-xl font-bold text-sm hover:bg-white/10 transition-all">Cancel</a>
                    <button type="submit" class="px-8 py-4 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-violet-500/25 transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
