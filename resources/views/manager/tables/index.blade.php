<x-manager-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-3xl font-black text-deep-blue tracking-tight">Table Management</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Manage your restaurant tables and QR codes</p>
        </div>
        <button onclick="openAddTableModal()" class="bg-orange-red text-white px-8 py-4 rounded-2xl font-black text-lg shadow-xl shadow-orange-red/30 hover:bg-deep-blue transition-all flex items-center gap-3">
            <i data-lucide="plus" class="w-6 h-6"></i> Add New Table
        </button>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($tables as $table)
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition-all group">
                <div class="p-8 pb-0">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl font-black text-deep-blue">{{ $loop->iteration }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="openEditTableModal({{ json_encode($table) }})" class="p-2 text-gray-400 hover:text-deep-blue hover:bg-gray-50 rounded-xl transition-all">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </button>
                            <form action="{{ route('manager.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <h4 class="text-xl font-black text-deep-blue mb-1">{{ $table->name }}</h4>
                    <p class="text-sm font-bold text-gray-400 mb-6">{{ $table->capacity }} Seats</p>
                </div>
                
                <div class="bg-gray-50 p-8 flex flex-col items-center gap-4">
                    <div class="bg-white p-2 rounded-xl shadow-sm">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($table->qr_code) }}" alt="QR Code" class="w-32 h-32">
                    </div>
                    <div class="flex gap-2 w-full">
                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode($table->qr_code) }}" download="table-{{ $table->id }}-qr.png" target="_blank" class="flex-1 bg-white text-deep-blue py-3 rounded-xl font-bold text-sm shadow-sm hover:bg-deep-blue hover:text-white transition-all flex items-center justify-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i> Download
                        </a>
                        <button onclick="copyLink('{{ $table->qr_code }}')" class="flex-1 bg-white text-deep-blue py-3 rounded-xl font-bold text-sm shadow-sm hover:bg-deep-blue hover:text-white transition-all flex items-center justify-center gap-2">
                            <i data-lucide="link" class="w-4 h-4"></i> Copy Link
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="layout-grid" class="w-10 h-10 text-gray-300"></i>
                </div>
                <h3 class="text-xl font-black text-deep-blue mb-2">No tables found</h3>
                <p class="text-gray-400">Start by adding your first table.</p>
            </div>
        @endforelse

        <!-- Add New Card -->
        <button onclick="openAddTableModal()" class="bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-12 hover:border-orange-red hover:bg-orange-50 transition-all group min-h-[400px]">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform mb-4">
                <i data-lucide="plus" class="w-8 h-8 text-gray-400 group-hover:text-orange-red"></i>
            </div>
            <span class="font-black text-gray-400 group-hover:text-orange-red uppercase tracking-widest text-xs">Add New Table</span>
        </button>
    </div>

    <!-- Table Modal -->
    <div id="tableModal" class="fixed inset-0 bg-deep-blue/40 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-xl font-black text-deep-blue">Add New Table</h3>
                    <button onclick="closeTableModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-all">
                        <i data-lucide="x" class="w-5 h-5 text-gray-400"></i>
                    </button>
                </div>
                
                <form id="tableForm" action="{{ route('manager.tables.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Table Name</label>
                        <input type="text" name="name" id="tableName" required placeholder="e.g. Table 1" 
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>
                    
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Capacity (Seats)</label>
                        <input type="number" name="capacity" id="tableCapacity" placeholder="e.g. 4" 
                               class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-deep-blue focus:ring-2 focus:ring-orange-red transition-all">
                    </div>

                    <div id="statusToggle" class="hidden flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="tableActive" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active</span>
                    </div>

                    <button type="submit" class="w-full bg-deep-blue text-white py-4 rounded-2xl font-black text-lg shadow-xl shadow-deep-blue/20 hover:bg-orange-red transition-all">
                        Save Table
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddTableModal() {
            document.getElementById('modalTitle').innerText = 'Add New Table';
            document.getElementById('tableForm').action = '{{ route("manager.tables.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('tableName').value = '';
            document.getElementById('tableCapacity').value = '';
            document.getElementById('statusToggle').classList.add('hidden');
            
            document.getElementById('tableModal').classList.remove('hidden');
            document.getElementById('tableModal').classList.add('flex');
        }

        function openEditTableModal(table) {
            document.getElementById('modalTitle').innerText = 'Edit Table';
            document.getElementById('tableForm').action = `/manager/tables/${table.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('tableName').value = table.name;
            document.getElementById('tableCapacity').value = table.capacity;
            document.getElementById('tableActive').checked = table.is_active;
            document.getElementById('statusToggle').classList.remove('hidden');
            
            document.getElementById('tableModal').classList.remove('hidden');
            document.getElementById('tableModal').classList.add('flex');
        }

        function closeTableModal() {
            document.getElementById('tableModal').classList.add('hidden');
            document.getElementById('tableModal').classList.remove('flex');
        }

        function copyLink(link) {
            navigator.clipboard.writeText(link).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    </script>
</x-manager-layout>
