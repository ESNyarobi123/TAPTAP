<x-manager-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-3xl font-black text-deep-blue tracking-tight">Waiters & Staff</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Manage your service team</p>
        </div>
        <button onclick="openAddWaiterModal()" class="bg-deep-blue text-white px-8 py-4 rounded-2xl font-black text-lg shadow-xl shadow-deep-blue/30 hover:bg-orange-red transition-all flex items-center gap-3">
            <i data-lucide="user-plus" class="w-6 h-6"></i> Add New Waiter
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($waiters as $waiter)
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 hover:shadow-xl transition-all group">
                <div class="flex items-center gap-6 mb-8">
                    <div class="w-20 h-20 bg-purple-100 rounded-[2rem] flex items-center justify-center font-black text-3xl text-purple-600 shadow-lg shadow-purple-100/50 group-hover:rotate-6 transition-transform">
                        {{ substr($waiter->name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-deep-blue">{{ $waiter->name }}</h4>
                        <p class="text-xs font-bold text-green-500 uppercase tracking-widest">Active Now</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Orders</p>
                        <p class="text-xl font-black text-deep-blue">{{ $waiter->orders_count }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tips</p>
                        <p class="text-xl font-black text-deep-blue">Tsh {{ number_format($waiter->tips()->sum('amount')) }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button class="flex-1 bg-gray-50 text-deep-blue py-3 rounded-xl font-bold hover:bg-deep-blue hover:text-white transition-all">View Profile</button>
                    <form action="{{ route('manager.waiters.destroy', $waiter->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this waiter?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-3 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="users" class="w-10 h-10 text-gray-300"></i>
                </div>
                <h3 class="text-xl font-black text-deep-blue mb-2">No waiters found</h3>
                <p class="text-gray-400">Add your first waiter to start taking orders.</p>
            </div>
        @endforelse
    </div>

    <!-- Add Waiter Modal -->
    <div id="addWaiterModal" class="fixed inset-0 bg-deep-blue/40 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-[3rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-10">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-deep-blue tracking-tight">Add New Waiter</h3>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Create a new service account</p>
                    </div>
                    <button onclick="closeAddWaiterModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-all">
                        <i data-lucide="x" class="w-6 h-6 text-gray-400"></i>
                    </button>
                </div>

                <form action="{{ route('manager.waiters.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Full Name</label>
                        <input type="text" name="name" required placeholder="e.g. John Doe" 
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Email Address</label>
                        <input type="email" name="email" required placeholder="e.g. john@taptap.com" 
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" 
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Confirm Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••" 
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>

                    <button type="submit" class="w-full bg-deep-blue text-white py-5 rounded-2xl font-black text-lg shadow-xl shadow-deep-blue/20 hover:bg-orange-red transition-all">
                        Create Waiter Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddWaiterModal() {
            document.getElementById('addWaiterModal').classList.remove('hidden');
            document.getElementById('addWaiterModal').classList.add('flex');
            lucide.createIcons();
        }

        function closeAddWaiterModal() {
            document.getElementById('addWaiterModal').classList.add('hidden');
            document.getElementById('addWaiterModal').classList.remove('flex');
        }
    </script>
</x-manager-layout>
