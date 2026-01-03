<x-waiter-layout>
    <x-slot name="header">
        Restaurant Menu
    </x-slot>

    <div class="mb-12">
        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
            <button class="px-8 py-4 bg-orange-red text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-lg shadow-orange-red/20">All Items</button>
            @foreach($categories as $category)
                <button class="px-8 py-4 bg-white text-gray-400 rounded-[1.5rem] font-black text-xs uppercase tracking-widest hover:text-deep-blue transition-all border border-gray-100 whitespace-nowrap shadow-sm">{{ $category->name }}</button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($menuItems as $item)
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 hover:shadow-2xl transition-all group">
                <div class="relative h-56 bg-gray-100 overflow-hidden">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i data-lucide="image" class="w-12 h-12"></i>
                        </div>
                    @endif
                    
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl text-[10px] font-black {{ $item->is_available ? 'text-green-500' : 'text-red-500' }} uppercase tracking-widest shadow-sm">
                        {{ $item->is_available ? 'Available' : 'Sold Out' }}
                    </div>

                    <div class="absolute bottom-4 left-4 bg-deep-blue/80 backdrop-blur-md px-4 py-2 rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-sm">
                        {{ $item->category->name }}
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-xl font-black text-deep-blue leading-tight">{{ $item->name }}</h4>
                        <span class="font-black text-orange-red whitespace-nowrap ml-2">Tsh {{ number_format($item->price) }}</span>
                    </div>
                    
                    <p class="text-sm text-gray-400 mb-8 line-clamp-2 font-medium">{{ $item->description }}</p>
                    
                    <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gray-50 rounded-xl flex items-center justify-center">
                                <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $item->preparation_time ?? '15' }} min</span>
                        </div>
                        
                        @if($item->is_available)
                            <div class="flex items-center gap-2 text-green-500">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-[9px] font-black uppercase tracking-widest">In Stock</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-red-500">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span class="text-[9px] font-black uppercase tracking-widest">Out of Stock</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-waiter-layout>
