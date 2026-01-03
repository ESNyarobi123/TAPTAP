<x-manager-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-3xl font-black text-deep-blue tracking-tight">Menu Management</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Manage your categories and dishes</p>
        </div>
        <button onclick="openAddMenuModal()" class="bg-orange-red text-white px-8 py-4 rounded-2xl font-black text-lg shadow-xl shadow-orange-red/30 hover:bg-deep-blue transition-all flex items-center gap-3">
            <i data-lucide="plus" class="w-6 h-6"></i> Add New Item
        </button>
    </div>

    <!-- Categories Tabs -->
    <div class="flex gap-4 mb-12 overflow-x-auto pb-2">
        <button class="px-8 py-3 bg-deep-blue text-white rounded-2xl font-bold shadow-lg shadow-deep-blue/20">All Items</button>
        @foreach($categories as $category)
            <button class="px-8 py-3 bg-white text-gray-400 rounded-2xl font-bold hover:text-deep-blue transition-all border border-gray-100 whitespace-nowrap">{{ $category->name }}</button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($menuItems as $item)
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition-all group">
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i data-lucide="image" class="w-12 h-12"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6 gap-2">
                        <button onclick="openEditMenuModal({{ json_encode($item) }})" class="bg-white text-deep-blue p-3 rounded-xl shadow-lg hover:bg-orange-red hover:text-white transition-all">
                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                        </button>
                        <form action="{{ route('manager.menu.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-white text-red-500 p-3 rounded-xl shadow-lg hover:bg-red-500 hover:text-white transition-all">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black {{ $item->is_available ? 'text-green-500' : 'text-red-500' }} uppercase tracking-widest">
                        {{ $item->is_available ? 'Available' : 'Sold Out' }}
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-xl font-black text-deep-blue">{{ $item->name }}</h4>
                        <span class="font-black text-orange-red">Tsh {{ number_format($item->price) }}</span>
                    </div>
                    <p class="text-sm text-gray-400 mb-6 line-clamp-2">{{ $item->description }}</p>
                    <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                        <div class="flex items-center gap-2">
                            <i data-lucide="tag" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-xs font-bold text-gray-400">{{ $item->category->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-xs font-bold text-gray-400">{{ $item->preparation_time ?? '15' }} min</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="utensils" class="w-10 h-10 text-gray-300"></i>
                </div>
                <h3 class="text-xl font-black text-deep-blue mb-2">No menu items found</h3>
                <p class="text-gray-400">Start by adding your first dish to the menu.</p>
            </div>
        @endforelse

        <!-- Add New Card -->
        <button onclick="openAddMenuModal()" class="bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-12 hover:border-orange-red hover:bg-orange-50 transition-all group min-h-[400px]">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform mb-4">
                <i data-lucide="plus" class="w-8 h-8 text-gray-400 group-hover:text-orange-red"></i>
            </div>
            <span class="font-black text-gray-400 group-hover:text-orange-red uppercase tracking-widest text-xs">Add New Dish</span>
        </button>
    </div>

    <!-- Menu Modal -->
    <div id="menuModal" class="fixed inset-0 bg-deep-blue/40 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-10">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 id="modalTitle" class="text-2xl font-black text-deep-blue tracking-tight">Add New Dish</h3>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Enter dish details below</p>
                    </div>
                    <button onclick="closeMenuModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-all">
                        <i data-lucide="x" class="w-6 h-6 text-gray-400"></i>
                    </button>
                </div>

                <form id="menuForm" action="{{ route('manager.menu.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Dish Name</label>
                            <input type="text" name="name" id="menuName" required placeholder="e.g. Grilled Chicken" 
                                   class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Category</label>
                            <select name="category_id" id="menuCategoryId" required 
                                    class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Price (Tsh)</label>
                            <input type="number" name="price" id="menuPrice" required placeholder="e.g. 15000" 
                                   class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Prep Time (min)</label>
                            <input type="number" name="preparation_time" id="menuPrepTime" placeholder="e.g. 15" 
                                   class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Description</label>
                            <textarea name="description" id="menuDescription" rows="4" placeholder="Describe the dish..." 
                                      class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all"></textarea>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Dish Image</label>
                            <div class="relative group">
                                <input type="file" name="image" id="menuImage" class="hidden" onchange="previewImage(this)">
                                <div onclick="document.getElementById('menuImage').click()" class="w-full h-32 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center cursor-pointer group-hover:border-orange-red transition-all overflow-hidden">
                                    <img id="imagePreview" class="hidden w-full h-full object-cover">
                                    <div id="uploadPlaceholder" class="flex flex-col items-center">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-300 group-hover:text-orange-red"></i>
                                        <span class="text-[10px] font-bold text-gray-400 mt-2">Click to upload</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="availabilityToggle" class="hidden flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_available" id="menuAvailable" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Available</span>
                        </div>
                    </div>

                    <div class="col-span-full mt-6">
                        <button type="submit" class="w-full bg-deep-blue text-white py-5 rounded-2xl font-black text-lg shadow-xl shadow-deep-blue/20 hover:bg-orange-red transition-all">
                            Save Menu Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddMenuModal() {
            document.getElementById('modalTitle').innerText = 'Add New Dish';
            document.getElementById('menuForm').action = '{{ route("manager.menu.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('menuName').value = '';
            document.getElementById('menuDescription').value = '';
            document.getElementById('menuPrice').value = '';
            document.getElementById('menuPrepTime').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadPlaceholder').classList.remove('hidden');
            document.getElementById('availabilityToggle').classList.add('hidden');
            
            document.getElementById('menuModal').classList.remove('hidden');
            document.getElementById('menuModal').classList.add('flex');
            lucide.createIcons();
        }

        function openEditMenuModal(item) {
            document.getElementById('modalTitle').innerText = 'Edit Dish';
            document.getElementById('menuForm').action = `/manager/menu/${item.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('menuName').value = item.name;
            document.getElementById('menuDescription').value = item.description || '';
            document.getElementById('menuPrice').value = item.price;
            document.getElementById('menuPrepTime').value = item.preparation_time || '';
            document.getElementById('menuCategoryId').value = item.category_id;
            document.getElementById('menuAvailable').checked = item.is_available;
            document.getElementById('availabilityToggle').classList.remove('hidden');

            if (item.image) {
                document.getElementById('imagePreview').src = `/storage/${item.image}`;
                document.getElementById('imagePreview').classList.remove('hidden');
                document.getElementById('uploadPlaceholder').classList.add('hidden');
            } else {
                document.getElementById('imagePreview').classList.add('hidden');
                document.getElementById('uploadPlaceholder').classList.remove('hidden');
            }
            
            document.getElementById('menuModal').classList.remove('hidden');
            document.getElementById('menuModal').classList.add('flex');
            lucide.createIcons();
        }

        function closeMenuModal() {
            document.getElementById('menuModal').classList.add('hidden');
            document.getElementById('menuModal').classList.remove('flex');
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-manager-layout>
