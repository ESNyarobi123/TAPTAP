<x-admin-layout>
    <x-slot name="header">
        Withdrawal Requests
    </x-slot>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-white/5 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-white tracking-tight">Restaurant Withdrawals</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mt-1">Review and process payout requests</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Restaurant</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Method</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-white/40 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-white/5 transition-all">
                        <td class="px-6 py-5">
                            <span class="font-bold text-white">{{ $withdrawal->restaurant->name }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-white font-black">Tsh {{ number_format($withdrawal->amount, 0) }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-white">{{ $withdrawal->payment_method ?? 'N/A' }}</span>
                                <span class="text-[9px] text-white/40 font-medium truncate w-32">{{ $withdrawal->payment_details }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $statusColor = match($withdrawal->status) {
                                    'approved' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'rejected' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                                    'paid' => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30',
                                    default => 'bg-white/10 text-white/60 border-white/20',
                                };
                            @endphp
                            <span class="px-3 py-1.5 rounded-full {{ $statusColor }} text-[10px] font-black uppercase tracking-widest border">
                                {{ $withdrawal->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-sm text-white/60 font-medium">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-5 text-right">
                            @if($withdrawal->status == 'pending')
                            <div class="flex justify-end gap-2">
                                <button onclick="openModal('approve', {{ $withdrawal->id }})" class="p-2 bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-xl transition-all" title="Approve">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                                <button onclick="openModal('reject', {{ $withdrawal->id }})" class="p-2 bg-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl transition-all" title="Reject">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            @else
                                <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center text-white/20 mb-4 border border-white/10">
                                    <i data-lucide="wallet" class="w-8 h-8"></i>
                                </div>
                                <p class="text-sm font-bold text-white">No withdrawal requests</p>
                                <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest mt-1">Everything is up to date</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-white/5">
            {{ $withdrawals->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div id="withdrawalModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center">
        <div class="glass-card rounded-2xl p-8 w-full max-w-md shadow-2xl border border-white/10">
            <h3 id="modalTitle" class="text-2xl font-black text-white tracking-tight mb-2">Process Withdrawal</h3>
            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-8">Add a note for the restaurant manager</p>
            
            <form id="modalForm" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 block">Admin Note</label>
                    <textarea name="admin_note" rows="4" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all" placeholder="e.g. Transaction completed via Bank Transfer..."></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeModal()" class="flex-1 py-4 glass text-white/60 rounded-xl font-bold text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" id="modalSubmit" class="flex-1 py-4 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-bold text-sm hover:shadow-lg transition-all">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(action, id) {
            const modal = document.getElementById('withdrawalModal');
            const form = document.getElementById('modalForm');
            const title = document.getElementById('modalTitle');
            const submit = document.getElementById('modalSubmit');
            
            title.innerText = action === 'approve' ? 'Approve Withdrawal' : 'Reject Withdrawal';
            submit.innerText = action === 'approve' ? 'Approve' : 'Reject';
            submit.className = action === 'approve' 
                ? 'flex-1 py-4 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white rounded-xl font-bold text-sm hover:shadow-lg transition-all' 
                : 'flex-1 py-4 bg-gradient-to-r from-rose-500 to-orange-500 text-white rounded-xl font-bold text-sm hover:shadow-lg transition-all';
            
            form.action = `/admin/withdrawals/${id}/${action}`;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('withdrawalModal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
