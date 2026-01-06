<x-manager-layout>
    <x-slot name="header">
        Feedback & Ratings
    </x-slot>

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white tracking-tight">Feedback & Ratings</h2>
        <p class="text-sm font-medium text-white/40 uppercase tracking-wider">What your customers are saying</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Overall Rating -->
        <div class="glass-card p-8 rounded-2xl flex flex-col items-center justify-center text-center relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-gradient-to-br from-amber-500/20 to-amber-500/5 rounded-full blur-2xl"></div>
            <p class="text-[11px] font-bold text-white/40 uppercase tracking-wider mb-4 relative z-10">Overall Rating</p>
            <h3 class="text-6xl font-bold text-white mb-4 relative z-10">{{ number_format($avgRating, 1) }}</h3>
            <div class="flex text-amber-400 mb-4 relative z-10">
                @for($i = 1; $i <= 5; $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="{{ $i <= round($avgRating) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $i <= round($avgRating) ? '' : 'text-white/20' }}">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                @endfor
            </div>
            <p class="text-[11px] font-medium text-white/40 uppercase tracking-wider relative z-10">Based on {{ number_format($totalReviews) }} reviews</p>
        </div>

        <!-- Rating Breakdown -->
        <div class="lg:col-span-2 glass-card p-8 rounded-2xl">
            <p class="text-[11px] font-bold text-white/40 uppercase tracking-wider mb-6">Rating Breakdown</p>
            <div class="space-y-4">
                @foreach([5, 4, 3, 2, 1] as $star)
                    @php
                        $count = $ratingBreakdown[$star] ?? 0;
                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-white/60 w-4">{{ $star }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-400">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        <div class="flex-1 bg-white/5 h-2 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-amber-500 to-amber-400 h-full rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm font-bold text-white/60 w-10">{{ round($percentage) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="space-y-4">
        @forelse($feedbacks as $feedback)
            <div class="glass-card p-6 rounded-2xl flex gap-6 card-hover">
                <div class="w-14 h-14 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center font-bold text-violet-400 text-xl shrink-0 border border-violet-500/20">
                    {{ substr($feedback->order->customer_name ?? 'C', 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="text-lg font-bold text-white">{{ $feedback->order->customer_name ?? 'Customer' }}</h4>
                            <p class="text-[11px] font-medium text-white/40">{{ $feedback->created_at->diffForHumans() }} • Order #TXN-{{ $feedback->order_id }}</p>
                        </div>
                        <div class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= $feedback->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $i <= $feedback->rating ? '' : 'text-white/20' }}">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-white/60 leading-relaxed font-medium">"{{ $feedback->comment }}"</p>
                    <div class="mt-4 flex gap-4">
                        <button class="text-[11px] font-bold text-violet-400 hover:text-violet-300 transition-colors flex items-center gap-2 uppercase tracking-wider">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            Reply
                        </button>
                        <button class="text-[11px] font-bold text-white/40 hover:text-rose-400 transition-colors flex items-center gap-2 uppercase tracking-wider">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/>
                            </svg>
                            Report
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass-card p-16 rounded-2xl text-center">
                <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/20">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No feedback yet</h3>
                <p class="text-white/40">Customer reviews will appear here once they start rating their experience.</p>
            </div>
        @endforelse
        <div class="p-4">
            {{ $feedbacks->links() }}
        </div>
    </div>
</x-manager-layout>
