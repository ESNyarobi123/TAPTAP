@extends('layouts.order-portal')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-3xl font-bold text-white tracking-tight">Live Orders</h2>
        <p class="text-sm font-medium text-white/40 uppercase tracking-wider">Pending → Preparing → Served → Completed</p>
    </div>
    <div class="flex gap-3">
        <button onclick="openCreateOrderModal()" class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-3 rounded-xl font-semibold transition-all flex items-center gap-2 shadow-lg shadow-violet-600/20">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Create Order
        </button>
        <button onclick="window.location.reload()" class="glass px-5 py-3 rounded-xl font-semibold text-white/70 hover:text-white hover:bg-white/10 transition-all flex items-center gap-2">Refresh</button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Pending -->
    <div class="glass-card p-5 rounded-2xl min-h-[500px]">
        <div class="flex items-center justify-between mb-5 px-1">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>
                <h4 class="font-bold text-white uppercase tracking-wider text-[11px]">Pending</h4>
            </div>
            <span class="bg-rose-500/20 text-rose-400 text-[11px] font-bold px-2.5 py-1 rounded-full border border-rose-500/20">{{ $pendingOrders->count() }}</span>
        </div>
        <div class="space-y-3">
            @forelse($pendingOrders as $order)
                @include('order-portal.partials.order-card', ['order' => $order, 'status' => 'pending'])
            @empty
                <p class="text-sm text-white/30 text-center py-8">No pending orders</p>
            @endforelse
        </div>
    </div>

    <!-- Preparing -->
    <div class="glass-card p-5 rounded-2xl min-h-[500px]">
        <div class="flex items-center justify-between mb-5 px-1">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                <h4 class="font-bold text-white uppercase tracking-wider text-[11px]">Preparing</h4>
            </div>
            <span class="bg-amber-500/20 text-amber-400 text-[11px] font-bold px-2.5 py-1 rounded-full border border-amber-500/20">{{ $preparingOrders->count() }}</span>
        </div>
        <div class="space-y-3">
            @forelse($preparingOrders as $order)
                @include('order-portal.partials.order-card', ['order' => $order, 'status' => 'preparing'])
            @empty
                <p class="text-sm text-white/30 text-center py-8">No orders in kitchen</p>
            @endforelse
        </div>
    </div>

    <!-- Served -->
    <div class="glass-card p-5 rounded-2xl min-h-[500px]">
        <div class="flex items-center justify-between mb-5 px-1">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <h4 class="font-bold text-white uppercase tracking-wider text-[11px]">Served</h4>
            </div>
            <span class="bg-emerald-500/20 text-emerald-400 text-[11px] font-bold px-2.5 py-1 rounded-full border border-emerald-500/20">{{ $servedOrders->count() }}</span>
        </div>
        <div class="space-y-3">
            @forelse($servedOrders as $order)
                @include('order-portal.partials.order-card', ['order' => $order, 'status' => 'served'])
            @empty
                <p class="text-sm text-white/30 text-center py-8">No served orders</p>
            @endforelse
        </div>
    </div>

    <!-- Completed -->
    <div class="glass-card p-5 rounded-2xl min-h-[500px]">
        <div class="flex items-center justify-between mb-5 px-1">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-cyan-500 rounded-full"></div>
                <h4 class="font-bold text-white uppercase tracking-wider text-[11px]">Completed</h4>
            </div>
            <span class="bg-cyan-500/20 text-cyan-400 text-[11px] font-bold px-2.5 py-1 rounded-full border border-cyan-500/20">{{ $paidOrders->count() }}</span>
        </div>
        <div class="space-y-3 opacity-60">
            @forelse($paidOrders as $order)
                <div class="glass p-4 rounded-xl">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-sm font-bold text-white">Table #{{ $order->table_number }}</span>
                        <span class="text-[10px] font-medium text-white/40">Tsh {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-white/30 text-center py-8">No completed orders today</p>
            @endforelse
        </div>
    </div>
</div>

@include('order-portal.partials.create-order-modal')
@include('order-portal.partials.edit-order-modal')
@include('order-portal.partials.edit-items-modal')
@include('order-portal.partials.payment-modal')
@endsection
