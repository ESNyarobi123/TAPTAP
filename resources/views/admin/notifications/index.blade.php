<x-admin-layout>
    <x-slot name="header">
        Push Notifications
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Notification Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                    <div class="mb-10">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Broadcast Message</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Send instant alerts to restaurant staff</p>
                    </div>

                    <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Notification Title</label>
                            <input type="text" name="title" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all" placeholder="e.g. System Maintenance Update" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Message Content</label>
                            <textarea name="message" rows="5" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all" placeholder="Type your message here..." required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Target Audience</label>
                                <select name="target" id="targetSelect" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                                    <option value="all">All Users</option>
                                    <option value="managers">All Managers</option>
                                    <option value="waiters">All Waiters</option>
                                    <option value="specific_restaurant">Specific Restaurant</option>
                                </select>
                            </div>

                            <div id="restaurantSelectContainer" class="space-y-2 hidden">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Select Restaurant</label>
                                <select name="restaurant_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all">
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pt-8 flex justify-end">
                            <button type="submit" class="px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-2xl hover:shadow-slate-900/20 transition-all flex items-center gap-3">
                                <i data-lucide="send" class="w-4 h-4"></i> Send Notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview & Tips -->
            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-slate-900/20">
                    <h3 class="text-xl font-black tracking-tighter mb-6">Mobile Preview</h3>
                    <div class="bg-white/10 rounded-3xl p-6 border border-white/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center text-slate-900">
                                <i data-lucide="bell" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/50">TAPTAP System</p>
                                <p class="text-xs font-bold">Just now</p>
                            </div>
                        </div>
                        <p class="text-sm font-black mb-1">Notification Title</p>
                        <p class="text-xs text-white/70 leading-relaxed">Your message content will appear here on the user's mobile device.</p>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-4">Pro Tips</h3>
                    <ul class="space-y-4">
                        <li class="flex gap-3">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0"></i>
                            <p class="text-xs text-slate-500 font-medium">Keep titles short and punchy for better engagement.</p>
                        </li>
                        <li class="flex gap-3">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0"></i>
                            <p class="text-xs text-slate-500 font-medium">Use specific targets to avoid spamming all users.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('targetSelect').addEventListener('change', function() {
            const container = document.getElementById('restaurantSelectContainer');
            if (this.value === 'specific_restaurant') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>
