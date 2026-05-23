<x-admin-layout>
    <x-slot name="header">Waiters & Unique Codes</x-slot>

    @include('admin.partials.page-styles')
    @include('admin.partials.flash')

    @include('admin.partials.page-hero', [
        'eyebrow' => 'People',
        'title' => 'Waiters & Unique Codes',
        'subtitle' => 'Platform-wide waiter registry with TIPTAP-W codes and venue links.',
        'accent' => 'amber',
    ])

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
        @include('admin.partials.stat-chip', ['label' => 'Total waiters', 'value' => number_format($waiters->total()), 'tone' => 'amber'])
        @include('admin.partials.stat-chip', ['label' => 'On this page', 'value' => $waiters->count(), 'tone' => 'cyan'])
        @include('admin.partials.stat-chip', ['label' => 'Search', 'value' => $search ?: '—', 'tone' => 'white'])
    </div>

    <div class="glass-card admin-data-panel rounded-3xl p-6 mb-6 border border-amber-500/15">
        <h3 class="text-sm font-black text-white mb-1">Lookup by unique number</h3>
        <p class="text-xs text-white/45 mb-4">Enter TIPTAP-W-xxxxx for instant profile preview.</p>
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="searchCode" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Unique number</label>
                <input type="text" id="searchCode" placeholder="TIPTAP-W-00001"
                       class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl font-mono text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
            </div>
            <button type="button" id="adminSearchBtn" onclick="adminSearchWaiter()" class="px-6 py-3 bg-gradient-to-r from-fin-primary to-fin-primary-dark text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-icon"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <span class="search-label">Search</span>
            </button>
        </div>
        <div id="searchResult" class="mt-4 hidden"></div>
        <div id="searchError" class="mt-4 hidden p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm"></div>
    </div>

    <div class="glass-card admin-data-panel rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-white/5 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Full directory</p>
            <form method="GET" action="{{ route('admin.waiters.index') }}" class="flex gap-3 w-full md:w-auto">
                <div class="relative flex-1 md:w-72">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Name, email, or TIPTAP-W-xxx"
                           class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 text-white/40">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </div>
                <button type="submit" class="px-5 py-3 bg-white/10 hover:bg-white/15 text-white rounded-xl font-semibold text-sm border border-white/10 transition-all">Search</button>
            </form>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[720px]">
                <thead>
                    <tr class="bg-white/5">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Waiter</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Unique Code</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Restaurant</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Waiter Code</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-white/40 uppercase tracking-widest">Orders</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-white/40 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($waiters as $waiter)
                        <tr class="admin-table-row group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 to-cyan-500/20 flex items-center justify-center text-amber-400 font-bold text-sm border border-white/10 shrink-0">
                                        {{ substr($waiter->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $waiter->name }}</p>
                                        <p class="text-[10px] text-white/40">{{ $waiter->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-sm font-mono font-bold text-cyan-400 bg-white/5 px-2 py-1 rounded-lg">{{ $waiter->global_waiter_number ?? '—' }}</code>
                            </td>
                            <td class="px-6 py-4">
                                @if($waiter->restaurant)
                                    <a href="{{ route('admin.restaurants.show', $waiter->restaurant) }}" class="text-sm font-medium text-white/70 hover:text-violet-400">{{ $waiter->restaurant->name }}</a>
                                @else
                                    <span class="text-sm text-white/40">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs font-mono text-white/60">{{ $waiter->waiter_code ?? '—' }}</code>
                            </td>
                            <td class="px-6 py-4">
                                @if($waiter->restaurant_id)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Linked
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-white/10 text-white/50 border border-white/10">Not linked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-white/70 text-sm tabular-nums">{{ $waiter->orders_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.users.show', $waiter) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-violet-600/20 hover:bg-violet-600/40 text-violet-200 text-xs font-semibold transition-all">
                                    View profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white/30"><i data-lucide="user-check" class="w-8 h-8"></i></div>
                                    <p class="text-white font-bold">No waiters found</p>
                                    <p class="text-sm text-white/50">@if($search) No match for "{{ $search }}". @else No waiters registered yet. @endif</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($waiters->hasPages())
            <div class="p-6 border-t border-white/5">{{ $waiters->links() }}</div>
        @endif
    </div>

    <script>
        function adminSearchWaiter() {
            const input = document.getElementById('searchCode');
            const q = (input?.value || '').trim();
            const resultEl = document.getElementById('searchResult');
            const errorEl = document.getElementById('searchError');
            const btn = document.getElementById('adminSearchBtn');
            const labelEl = btn ? btn.querySelector('.search-label') : null;

            resultEl.classList.add('hidden');
            resultEl.innerHTML = '';
            errorEl.classList.add('hidden');
            errorEl.textContent = '';

            if (!q) {
                errorEl.textContent = 'Enter the unique number (TIPTAP-W-xxxxx).';
                errorEl.classList.remove('hidden');
                return;
            }

            if (btn) { btn.disabled = true; if (labelEl) labelEl.textContent = 'Searching…'; }

            fetch('{{ route('admin.waiters.search') }}?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        errorEl.textContent = data.message || 'Waiter not found.';
                        errorEl.classList.remove('hidden');
                        return;
                    }
                    const w = data.data;
                    resultEl.innerHTML = `
                        <div class="p-5 rounded-2xl bg-white/5 border border-cyan-500/20">
                            <div class="flex flex-wrap items-start gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/20 to-cyan-500/20 flex items-center justify-center text-amber-400 font-bold border border-white/10">${(w.name || '').charAt(0)}</div>
                                    <div>
                                        <p class="font-bold text-white">${w.name || '—'}</p>
                                        <p class="text-xs text-white/50">${w.email || '—'}</p>
                                        <p class="text-sm font-mono text-cyan-400 mt-1">${w.global_waiter_number || '—'}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-white/70 space-y-1">
                                    <p><strong class="text-white/80">Restaurant:</strong> ${w.current_restaurant || '—'}</p>
                                    <p><strong class="text-white/80">Waiter code:</strong> ${w.waiter_code || '—'}</p>
                                    <p><strong class="text-white/80">Orders:</strong> ${w.orders_count ?? 0} · <strong class="text-white/80">Feedback:</strong> ${w.feedback_count ?? 0}</p>
                                </div>
                                <a href="/admin/users/${w.id}" class="ml-auto px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white rounded-lg text-sm font-semibold transition-all">View profile</a>
                            </div>
                        </div>`;
                    resultEl.classList.remove('hidden');
                })
                .catch(function() {
                    errorEl.textContent = 'Error while searching. Please try again.';
                    errorEl.classList.remove('hidden');
                })
                .finally(function() {
                    if (btn) { btn.disabled = false; if (labelEl) labelEl.textContent = 'Search'; }
                });
        }
    </script>
</x-admin-layout>
