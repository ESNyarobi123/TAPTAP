<x-admin-layout>
    <x-slot name="header">
        Withdrawal Requests
    </x-slot>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tighter">Restaurant Withdrawals</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Review and process payout requests</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Restaurant</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Method</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-8 py-6">
                            <span class="font-bold text-slate-900">{{ $withdrawal->restaurant->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm text-slate-900 font-black">Tsh {{ number_format($withdrawal->amount, 0) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-900">{{ $withdrawal->payment_method ?? 'N/A' }}</span>
                                <span class="text-[9px] text-slate-400 font-medium truncate w-32">{{ $withdrawal->payment_details }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $statusColor = match($withdrawal->status) {
                                    'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                    'rejected' => 'bg-red-50 text-red-600 border-red-100',
                                    'paid' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100',
                                };
                            @endphp
                            <span class="px-4 py-1.5 rounded-full {{ $statusColor }} text-[10px] font-black uppercase tracking-widest border">
                                {{ $withdrawal->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500 font-medium">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                        <td class="px-8 py-6 text-right">
                            @if($withdrawal->status == 'pending')
                            <div class="flex justify-end gap-2">
                                <button onclick="openModal('approve', {{ $withdrawal->id }})" class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl transition-all" title="Approve">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                                <button onclick="openModal('reject', {{ $withdrawal->id }})" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all" title="Reject">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            @else
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4">
                                    <i data-lucide="wallet" class="w-8 h-8"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-900">No withdrawal requests</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Everything is up to date</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-8 border-t border-slate-50">
            {{ $withdrawals->links() }}
        </div>
    </div>

    <!-- Modal (Simple implementation using JS) -->
    <div id="withdrawalModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] hidden flex items-center justify-center">
        <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-md shadow-2xl">
            <h3 id="modalTitle" class="text-2xl font-black text-slate-900 tracking-tighter mb-2">Process Withdrawal</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8">Add a note for the restaurant manager</p>
            
            <form id="modalForm" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Admin Note</label>
                    <textarea name="admin_note" rows="4" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-slate-900 transition-all" placeholder="e.g. Transaction completed via Bank Transfer..."></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-slate-50 text-slate-400 rounded-2xl font-bold text-sm hover:bg-slate-100 transition-all">Cancel</button>
                    <button type="submit" id="modalSubmit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-2xl transition-all">Confirm</button>
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
            submit.className = action === 'approve' ? 'flex-1 py-4 bg-emerald-600 text-white rounded-2xl font-bold text-sm hover:shadow-2xl transition-all' : 'flex-1 py-4 bg-red-600 text-white rounded-2xl font-bold text-sm hover:shadow-2xl transition-all';
            
            form.action = `/admin/withdrawals/${id}/${action}`;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('withdrawalModal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
