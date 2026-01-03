<x-waiter-layout>
    <x-slot name="header">
        My Tips History
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-1">
            <div class="bg-deep-blue p-10 rounded-[3rem] shadow-2xl shadow-deep-blue/20 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-red/20 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest mb-2">Total Tips Earned</p>
                    <h3 class="text-4xl font-black mb-6">Tsh {{ number_format($totalTips) }}</h3>
                    <div class="bg-white/10 p-4 rounded-2xl border border-white/10">
                        <p class="text-[9px] font-bold text-blue-200 uppercase tracking-widest">Keep up the great service!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Date</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Order ID</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tips as $tip)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-10 py-6">
                            <p class="font-bold text-deep-blue text-sm">{{ $tip->created_at->format('M d, Y') }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $tip->created_at->format('H:i A') }}</p>
                        </td>
                        <td class="px-10 py-6">
                            <span class="text-xs font-black text-gray-400">#{{ $tip->order_id }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <p class="font-black text-orange-red">Tsh {{ number_format($tip->amount) }}</p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-10 py-20 text-center">
                            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">No tips recorded yet</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-10 py-6 border-t border-gray-50">
            {{ $tips->links() }}
        </div>
    </div>
</x-waiter-layout>
