<x-admin-layout>
    <x-slot name="header">
        Push Notifications
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Notification Form -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-8">
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-white tracking-tight">Broadcast Message</h3>
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mt-1">Send instant alerts to restaurant staff</p>
                    </div>

                    <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Notification Title</label>
                            <input type="text" name="title" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all" placeholder="e.g. System Maintenance Update" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Message Content</label>
                            <textarea name="message" rows="5" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all" placeholder="Type your message here..." required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Target Audience</label>
                                <select name="target" id="targetSelect" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all [&>option]:text-black">
                                    <option value="all">All Users</option>
                                    <option value="managers">All Managers</option>
                                    <option value="waiters">All Waiters</option>
                                    <option value="specific_restaurant">Specific Restaurant</option>
                                </select>
                            </div>

                            <div id="restaurantSelectContainer" class="space-y-2 hidden">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Select Restaurant</label>
                                <select name="restaurant_id" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all [&>option]:text-black">
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-10 py-4 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-violet-500/25 transition-all flex items-center gap-3">
                                <i data-lucide="send" class="w-4 h-4"></i> Send Notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview & Tips -->
            <div class="space-y-8">
                <div class="glass-card rounded-2xl p-6 border border-violet-500/20">
                    <h3 class="text-xl font-black text-white tracking-tight mb-6">Mobile Preview</h3>
                    <div class="bg-white/5 rounded-xl p-5 border border-white/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-violet-600 to-cyan-500 rounded-xl flex items-center justify-center text-white">
                                <i data-lucide="bell" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/50">TIPTAP System</p>
                                <p class="text-xs font-bold text-white">Just now</p>
                            </div>
                        </div>
                        <p class="text-sm font-black text-white mb-1">Notification Title</p>
                        <p class="text-xs text-white/60 leading-relaxed">Your message content will appear here on the user's mobile device.</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-xl font-black text-white tracking-tight mb-4">Pro Tips</h3>
                    <ul class="space-y-4">
                        <li class="flex gap-3">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 shrink-0"></i>
                            <p class="text-xs text-white/60 font-medium">Keep titles short and punchy for better engagement.</p>
                        </li>
                        <li class="flex gap-3">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 shrink-0"></i>
                            <p class="text-xs text-white/60 font-medium">Use specific targets to avoid spamming all users.</p>
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
