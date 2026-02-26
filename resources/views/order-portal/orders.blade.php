@extends('layouts.order-portal')

@section('content')
{{-- Stats bar (optional) --}}
@php
    $totalActive = $pendingOrders->count() + $preparingOrders->count() + $servedOrders->count();
@endphp
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Live Orders</h1>
        <p class="text-xs sm:text-sm font-medium text-white/40 uppercase tracking-wider mt-0.5">Pending → Preparing → Served → Completed</p>
    </div>
    <div class="flex items-center gap-2 sm:gap-3">
        <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-xs font-semibold text-white/70">
            <span class="text-white font-bold">{{ $totalActive }}</span> active
        </span>
        <button type="button" onclick="window.location.reload()" class="p-2.5 sm:px-4 sm:py-2.5 rounded-xl glass hover:bg-white/10 text-white/70 hover:text-white transition-all touch-action-manipulation" title="Refresh" aria-label="Refresh">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </button>
        <button type="button" onclick="openCreateOrderModal()" class="hidden sm:flex items-center gap-2 bg-gradient-to-r from-violet-600 to-cyan-600 hover:from-violet-500 hover:to-cyan-500 text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-violet-500/25 transition-all touch-action-manipulation">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
            Create Order
        </button>
    </div>
</div>

{{-- Kanban: horizontal scroll on mobile/tablet, 4-col grid on desktop --}}
<div class="flex gap-4 overflow-x-auto pb-20 sm:pb-4 lg:pb-0 scrollbar-hide custom-scrollbar lg:grid lg:grid-cols-4 lg:gap-6 lg:overflow-visible -mx-4 px-4 sm:-mx-6 sm:px-6 lg:mx-0 lg:px-0" style="scroll-snap-type: x mandatory;">
    {{-- Pending --}}
    <div class="flex-shrink-0 w-[min(100%,300px)] sm:w-[320px] lg:w-auto scroll-snap-align-start lg:min-w-0">
        <div class="glass-card rounded-2xl p-4 sm:p-5 min-h-[420px] lg:min-h-[520px] flex flex-col">
            <div class="flex items-center justify-between mb-4 px-0.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-rose-500 rounded-full animate-pulse" aria-hidden="true"></span>
                    <h2 class="font-bold text-white uppercase tracking-wider text-xs">Pending</h2>
                </div>
                <span class="bg-rose-500/20 text-rose-400 text-xs font-bold px-2.5 py-1 rounded-full border border-rose-500/20">{{ $pendingOrders->count() }}</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar min-h-0">
                @forelse($pendingOrders as $order)
                    @include('order-portal.partials.order-card', ['order' => $order, 'status' => 'pending'])
                @empty
                    <p class="text-sm text-white/30 text-center py-10">No pending orders</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Preparing --}}
    <div class="flex-shrink-0 w-[min(100%,300px)] sm:w-[320px] lg:w-auto scroll-snap-align-start lg:min-w-0">
        <div class="glass-card rounded-2xl p-4 sm:p-5 min-h-[420px] lg:min-h-[520px] flex flex-col">
            <div class="flex items-center justify-between mb-4 px-0.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-amber-500 rounded-full animate-pulse" aria-hidden="true"></span>
                    <h2 class="font-bold text-white uppercase tracking-wider text-xs">Preparing</h2>
                </div>
                <span class="bg-amber-500/20 text-amber-400 text-xs font-bold px-2.5 py-1 rounded-full border border-amber-500/20">{{ $preparingOrders->count() }}</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar min-h-0">
                @forelse($preparingOrders as $order)
                    @include('order-portal.partials.order-card', ['order' => $order, 'status' => 'preparing'])
                @empty
                    <p class="text-sm text-white/30 text-center py-10">No orders in kitchen</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Served --}}
    <div class="flex-shrink-0 w-[min(100%,300px)] sm:w-[320px] lg:w-auto scroll-snap-align-start lg:min-w-0">
        <div class="glass-card rounded-2xl p-4 sm:p-5 min-h-[420px] lg:min-h-[520px] flex flex-col">
            <div class="flex items-center justify-between mb-4 px-0.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse" aria-hidden="true"></span>
                    <h2 class="font-bold text-white uppercase tracking-wider text-xs">Served</h2>
                </div>
                <span class="bg-emerald-500/20 text-emerald-400 text-xs font-bold px-2.5 py-1 rounded-full border border-emerald-500/20">{{ $servedOrders->count() }}</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar min-h-0">
                @forelse($servedOrders as $order)
                    @include('order-portal.partials.order-card', ['order' => $order, 'status' => 'served'])
                @empty
                    <p class="text-sm text-white/30 text-center py-10">No served orders</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Completed --}}
    <div class="flex-shrink-0 w-[min(100%,300px)] sm:w-[320px] lg:w-auto scroll-snap-align-start lg:min-w-0">
        <div class="glass-card rounded-2xl p-4 sm:p-5 min-h-[420px] lg:min-h-[520px] flex flex-col opacity-95">
            <div class="flex items-center justify-between mb-4 px-0.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-cyan-500 rounded-full" aria-hidden="true"></span>
                    <h2 class="font-bold text-white uppercase tracking-wider text-xs">Completed</h2>
                </div>
                <span class="bg-cyan-500/20 text-cyan-400 text-xs font-bold px-2.5 py-1 rounded-full border border-cyan-500/20">{{ $paidOrders->count() }}</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar min-h-0">
                @forelse($paidOrders as $order)
                    <div class="glass p-4 rounded-xl">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-white">Table #{{ $order->table_number }}</span>
                            <span class="text-xs font-medium text-white/50">Tsh {{ number_format($order->total_amount) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-white/30 text-center py-10">No completed orders today</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- FAB: Create Order (mobile only) --}}
<button type="button" onclick="openCreateOrderModal()" class="sm:hidden fixed bottom-6 right-6 z-20 w-14 h-14 rounded-2xl bg-gradient-to-r from-violet-600 to-cyan-600 text-white shadow-xl shadow-violet-500/30 flex items-center justify-center touch-action-manipulation active:scale-95 transition-transform" style="bottom: max(1.5rem, env(safe-area-inset-bottom));" aria-label="Create order">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
</button>

@include('order-portal.partials.create-order-modal')
@include('order-portal.partials.edit-order-modal')
@include('order-portal.partials.edit-items-modal')
@include('order-portal.partials.payment-modal')
@endsection
