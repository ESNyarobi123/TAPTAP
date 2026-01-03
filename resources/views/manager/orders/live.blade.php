<x-manager-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-3xl font-black text-deep-blue tracking-tight">Live Orders</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Real-time order management</p>
        </div>
        <div class="flex gap-4">
            <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-xl border border-green-100">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-xs font-black text-green-600 uppercase tracking-widest">Live Sync Active</span>
            </div>
            <button onclick="window.location.reload()" class="bg-white border border-gray-100 px-6 py-4 rounded-2xl font-bold text-gray-600 hover:border-orange-red transition-all flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Live Kanban Board -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Pending -->
        <div class="bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100 min-h-[600px]">
            <div class="flex items-center justify-between mb-8 px-2">
                <h4 class="font-black text-deep-blue uppercase tracking-widest text-xs">Pending</h4>
                <span class="bg-orange-red text-white text-[10px] font-black px-2.5 py-1 rounded-full">{{ $pendingOrders->count() }}</span>
            </div>
            <div class="space-y-6">
                @forelse($pendingOrders as $order)
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-orange-50 text-orange-red px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Table #{{ $order->table_number }}</div>
                            <span class="text-[10px] font-bold text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="space-y-2 mb-6">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="font-bold text-deep-blue">{{ $item->quantity }}x {{ $item->menuItem->name }}</span>
                                    <span class="text-gray-400">Tsh {{ number_format($item->total) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <span class="font-black text-deep-blue">Tsh {{ number_format($order->total_amount) }}</span>
                            <button class="bg-deep-blue text-white p-2 rounded-xl hover:bg-orange-red transition-all">
                                <i data-lucide="play" class="w-4 h-4 fill-current"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-8">No pending orders</p>
                @endforelse
            </div>
        </div>

        <!-- Preparing -->
        <div class="bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100 min-h-[600px]">
            <div class="flex items-center justify-between mb-8 px-2">
                <h4 class="font-black text-deep-blue uppercase tracking-widest text-xs">Preparing</h4>
                <span class="bg-yellow-orange text-white text-[10px] font-black px-2.5 py-1 rounded-full">{{ $preparingOrders->count() }}</span>
            </div>
            <div class="space-y-6">
                @forelse($preparingOrders as $order)
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-yellow-50 text-yellow-orange px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Table #{{ $order->table_number }}</div>
                            <span class="text-[10px] font-bold text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="space-y-2 mb-6">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="font-bold text-deep-blue">{{ $item->quantity }}x {{ $item->menuItem->name }}</span>
                                    <span class="text-gray-400">Tsh {{ number_format($item->total) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-orange rounded-full animate-ping"></span>
                                <span class="text-[10px] font-black text-yellow-orange uppercase tracking-widest">In Kitchen</span>
                            </div>
                            <button class="bg-green-500 text-white p-2 rounded-xl hover:bg-deep-blue transition-all">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-8">No orders in kitchen</p>
                @endforelse
            </div>
        </div>

        <!-- Ready / Served -->
        <div class="bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100 min-h-[600px]">
            <div class="flex items-center justify-between mb-8 px-2">
                <h4 class="font-black text-deep-blue uppercase tracking-widest text-xs">Served</h4>
                <span class="bg-green-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full">{{ $servedOrders->count() }}</span>
            </div>
            <div class="space-y-6">
                @forelse($servedOrders as $order)
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-green-50 text-green-500 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Table #{{ $order->table_number }}</div>
                            <span class="text-[10px] font-bold text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="space-y-2 mb-6">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="font-bold text-deep-blue">{{ $item->quantity }}x {{ $item->menuItem->name }}</span>
                                </div>
                            @endforeach
                        </div>
                        <button onclick="openPaymentModal({{ $order->id }}, {{ $order->total_amount }})" 
                                class="w-full bg-purple-600 text-white py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-purple-200 hover:bg-deep-blue transition-all">
                            Process Payment
                        </button>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-8">No served orders</p>
                @endforelse
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100 min-h-[600px]">
            <div class="flex items-center justify-between mb-8 px-2">
                <h4 class="font-black text-deep-blue uppercase tracking-widest text-xs">Completed</h4>
                <span class="bg-blue-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full">{{ $paidOrders->count() }}</span>
            </div>
            <div class="space-y-6 opacity-60">
                @forelse($paidOrders as $order)
                    <div class="bg-white p-6 rounded-3xl border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black text-deep-blue">Table #{{ $order->table_number }}</span>
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-green-500"></i>
                        </div>
                        <p class="text-xs font-bold text-gray-400">Tsh {{ number_format($order->total_amount) }} • Paid</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-8">No completed orders today</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-deep-blue/40 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-[3rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-10">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-deep-blue tracking-tight">Process Payment</h3>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">ZenoPay USSD Push</p>
                    </div>
                    <button onclick="closePaymentModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-all">
                        <i data-lucide="x" class="w-6 h-6 text-gray-400"></i>
                    </button>
                </div>

                <div class="bg-gray-50 p-6 rounded-3xl mb-8 flex justify-between items-center">
                    <span class="font-bold text-gray-500">Total Amount</span>
                    <span id="modalAmount" class="text-2xl font-black text-orange-red">Tsh 0</span>
                </div>

                <form id="zenoPayForm" class="space-y-6">
                    <input type="hidden" id="modalOrderId">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Customer Phone (07XXXXXXXX)</label>
                        <input type="text" id="customerPhone" required placeholder="e.g. 0744963858" 
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Customer Name</label>
                        <input type="text" id="customerName" required placeholder="e.g. John Doe" 
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>
                    <button type="submit" id="payButton" class="w-full bg-deep-blue text-white py-5 rounded-2xl font-black text-lg shadow-xl shadow-deep-blue/20 hover:bg-orange-red transition-all flex items-center justify-center gap-3">
                        <i data-lucide="smartphone" class="w-6 h-6"></i> Send USSD Push
                    </button>
                </form>

                <div id="pollingStatus" class="hidden mt-8 p-6 bg-blue-50 rounded-3xl border border-blue-100 text-center">
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-sm font-bold text-blue-900">Waiting for customer to enter PIN...</p>
                        <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest">Do not close this window</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let pollingInterval = null;

        function openPaymentModal(orderId, amount) {
            document.getElementById('modalOrderId').value = orderId;
            document.getElementById('modalAmount').innerText = 'Tsh ' + new Intl.NumberFormat().format(amount);
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
            lucide.createIcons();
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
            if (pollingInterval) clearInterval(pollingInterval);
            document.getElementById('zenoPayForm').classList.remove('hidden');
            document.getElementById('pollingStatus').classList.add('hidden');
        }

        document.getElementById('zenoPayForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const payButton = document.getElementById('payButton');
            const orderId = document.getElementById('modalOrderId').value;
            const phone = document.getElementById('customerPhone').value;
            const name = document.getElementById('customerName').value;

            payButton.disabled = true;
            payButton.innerHTML = '<i class="w-6 h-6 animate-spin" data-lucide="loader-2"></i> Processing...';
            lucide.createIcons();

            try {
                const response = await fetch('{{ route("manager.payments.zenopay.initiate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        phone: phone,
                        name: name,
                        email: 'customer@taptap.com' // Default email
                    })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    document.getElementById('zenoPayForm').classList.add('hidden');
                    document.getElementById('pollingStatus').classList.remove('hidden');
                    startPolling(orderId);
                } else {
                    alert('Error: ' + (result.message || 'Failed to initiate payment'));
                    payButton.disabled = false;
                    payButton.innerHTML = '<i data-lucide="smartphone" class="w-6 h-6"></i> Send USSD Push';
                    lucide.createIcons();
                }
            } catch (error) {
                alert('Connection error. Please try again.');
                payButton.disabled = false;
                payButton.innerHTML = '<i data-lucide="smartphone" class="w-6 h-6"></i> Send USSD Push';
                lucide.createIcons();
            }
        });

        function startPolling(orderId) {
            pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch(`/manager/payments/zenopay/status/${orderId}`);
                    const result = await response.json();

                    if (result.status === 'paid') {
                        clearInterval(pollingInterval);
                        alert('Payment Successful!');
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Polling error:', error);
                }
            }, 5000); // Poll every 5 seconds
        }
    </script>
</x-manager-layout>
