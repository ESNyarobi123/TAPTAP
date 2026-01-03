<x-admin-layout>
    <x-slot name="header">
        Edit Restaurant
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
            <div class="mb-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Restaurant Settings</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Update restaurant profile and configuration</p>
            </div>

            <form action="{{ route('admin.restaurants.update', $restaurant) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Restaurant Name</label>
                        <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all" required>
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 ml-4">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $restaurant->phone) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                        @error('phone') <p class="text-red-500 text-[10px] font-bold mt-1 ml-4">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Location / Address</label>
                    <input type="text" name="location" value="{{ old('location', $restaurant->location) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                    @error('location') <p class="text-red-500 text-[10px] font-bold mt-1 ml-4">{{ $message }}</p> @enderror
                </div>

                <div class="pt-8 border-t border-slate-50">
                    <div class="mb-6">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Payment Gateway (ZenoPay)</h4>
                        <p class="text-[10px] text-slate-400 font-bold mt-1">Configure the API key for processing payments</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">ZenoPay API Key</label>
                        <input type="text" name="zenopay_api_key" value="{{ old('zenopay_api_key', $restaurant->zenopay_api_key) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-mono focus:ring-2 focus:ring-slate-900 transition-all" placeholder="Enter API Key">
                        @error('zenopay_api_key') <p class="text-red-500 text-[10px] font-bold mt-1 ml-4">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-8">
                    <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="px-8 py-4 bg-slate-50 text-slate-400 rounded-2xl font-bold text-sm hover:bg-slate-100 transition-all">Cancel</a>
                    <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-2xl hover:shadow-slate-900/20 transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
