<x-waiter-layout>
    <x-slot name="header">
        Customer Feedback
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($feedbacks as $feedback)
            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 hover:shadow-xl transition-all group">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex text-yellow-orange">
                        @for($i = 1; $i <= 5; $i++)
                            <i data-lucide="star" class="w-4 h-4 {{ $i <= $feedback->rating ? 'fill-current' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">{{ $feedback->created_at->diffForHumans() }}</span>
                </div>
                
                <p class="text-gray-600 italic font-medium leading-relaxed mb-8">"{{ $feedback->comment }}"</p>
                
                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center font-black text-deep-blue text-xs">
                            #{{ $feedback->order->table_number }}
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Table Number</p>
                    </div>
                    <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-red opacity-0 group-hover:opacity-100 transition-all">
                        <i data-lucide="heart" class="w-4 h-4 fill-current"></i>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-gray-100">
                <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="message-square" class="w-10 h-10 text-gray-200"></i>
                </div>
                <h4 class="text-xl font-black text-gray-300">No feedback yet</h4>
                <p class="text-sm text-gray-400 font-medium">Keep providing great service to get ratings!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $feedbacks->links() }}
    </div>
</x-waiter-layout>
