<x-manager-layout>
    <div class="mb-12">
        <h2 class="text-3xl font-black text-deep-blue tracking-tight">Feedback & Rates</h2>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">What your customers are saying</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mb-12">
        <!-- Overall Rating -->
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Overall Rating</p>
            <h3 class="text-6xl font-black text-deep-blue mb-4">{{ number_format($avgRating, 1) }}</h3>
            <div class="flex text-yellow-orange mb-4">
                @for($i = 1; $i <= 5; $i++)
                    <i data-lucide="star" class="w-6 h-6 {{ $i <= round($avgRating) ? 'fill-current' : '' }}"></i>
                @endfor
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Based on {{ number_format($totalReviews) }} reviews</p>
        </div>

        <!-- Rating Breakdown -->
        <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Rating Breakdown</p>
            <div class="space-y-4">
                @foreach([5, 4, 3, 2, 1] as $star)
                    @php
                        $count = $ratingBreakdown[$star] ?? 0;
                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-gray-400 w-4">{{ $star }}</span>
                        <i data-lucide="star" class="w-3 h-3 text-yellow-orange fill-current"></i>
                        <div class="flex-1 bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-yellow-orange h-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-gray-400 w-10">{{ round($percentage) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="space-y-6">
        @forelse($feedbacks as $feedback)
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex gap-8">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center font-black text-orange-red text-xl shrink-0">
                    {{ substr($feedback->order->customer_name ?? 'C', 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="text-xl font-black text-deep-blue">{{ $feedback->order->customer_name ?? 'Customer' }}</h4>
                            <p class="text-xs font-bold text-gray-400">{{ $feedback->created_at->diffForHumans() }} • Order #TXN-{{ $feedback->order_id }}</p>
                        </div>
                        <div class="flex text-yellow-orange">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= $feedback->rating ? 'fill-current' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed font-medium">"{{ $feedback->comment }}"</p>
                    <div class="mt-6 flex gap-4">
                        <button class="text-xs font-bold text-deep-blue hover:text-orange-red transition-colors flex items-center gap-2">
                            <i data-lucide="message-square" class="w-4 h-4"></i> Reply to Customer
                        </button>
                        <button class="text-xs font-bold text-gray-400 hover:text-orange-red transition-colors flex items-center gap-2">
                            <i data-lucide="flag" class="w-4 h-4"></i> Report
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-20 rounded-[3rem] shadow-sm border border-gray-100 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="message-square" class="w-10 h-10 text-gray-300"></i>
                </div>
                <h3 class="text-xl font-black text-deep-blue mb-2">No feedback yet</h3>
                <p class="text-gray-400">Customer reviews will appear here once they start rating their experience.</p>
            </div>
        @endforelse
        <div class="p-6">
            {{ $feedbacks->links() }}
        </div>
    </div>
</x-manager-layout>
