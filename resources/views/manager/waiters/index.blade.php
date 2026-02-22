<x-manager-layout>
    <x-slot name="header">Waiters & Staff</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">{{ session('error') }}</div>
    @endif

    <!-- Link Waiter Card -->
    <div class="glass-card rounded-2xl p-6 mb-8 border border-white/10">
        <h3 class="text-lg font-bold text-white mb-1">Link Waiter</h3>
        <p class="text-sm text-white/50 mb-2">Waiter anajisajili kwenye web, kisha anakupa nambari yake ya pekee (TIPTAP-W-xxxxx). Tafuta hapa na uunganishe na restaurant yako.</p>
        <p class="text-xs text-white/40 mb-4">Chagua <strong class="text-white/60">Muda mrefu</strong> (permanent) au <strong class="text-white/60">Show-time</strong> (muda maalum – weka tarehe ya mwisho).</p>
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="searchCode" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2 block">Nambari ya pekee ya waiter</label>
                <input type="text" id="searchCode" placeholder="TIPTAP-W-00001"
                       class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl font-mono text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
            </div>
            <button type="button" onclick="searchWaiter()" class="px-6 py-3 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                Tafuta
            </button>
        </div>
        <div id="searchResult" class="mt-4 hidden"></div>
        <div id="searchError" class="mt-4 hidden p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm"></div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Waiters waliounganishwa</h2>
            <p class="text-sm font-medium text-white/40 uppercase tracking-wider">Unlink kuondoa waiter; history inabaki</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($waiters as $waiter)
            <div class="glass-card p-6 rounded-2xl card-hover group">
                <div class="flex items-center gap-5 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center font-bold text-2xl text-violet-400 border border-violet-500/20 group-hover:scale-110 transition-transform">
                        {{ substr($waiter->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xl font-bold text-white truncate">{{ $waiter->name }}</h4>
                        <p class="text-[11px] font-mono text-cyan-400">{{ $waiter->global_waiter_number ?? '—' }}</p>
                        <p class="text-[11px] font-medium text-emerald-400 uppercase tracking-wider flex items-center gap-1.5 mt-1">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            Active
                        </p>
                        @if($waiter->employment_type === 'temporary' && $waiter->linked_until)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30 mt-1">Show-time · mpaka {{ $waiter->linked_until->format('d/m/Y') }}</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-white/10 text-white/60 border border-white/10 mt-1">Muda mrefu</span>
                        @endif
                    </div>
                </div>

                @if($waiter->waiter_code)
                <div class="mb-4 bg-white/5 rounded-xl overflow-hidden border border-white/10">
                    <div class="p-3 bg-white/5 border-b border-white/5 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Service Tag</span>
                        <div class="flex items-center gap-2">
                            <code class="text-sm font-mono font-bold text-cyan-400">{{ $waiter->waiter_code }}</code>
                            <button onclick="copyToClipboard('{{ $waiter->waiter_code }}', this)" class="text-white/40 hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 flex items-center gap-4">
                        <div class="bg-white p-2 rounded-lg shrink-0">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($waiter->waiter_qr_url) }}" alt="QR" class="w-16 h-16">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] text-white/40 mb-2 truncate">Scan to order with {{ explode(' ', $waiter->name)[0] }}</p>
                            <div class="flex gap-2">
                                <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode($waiter->waiter_qr_url) }}" download="waiter-{{ $waiter->waiter_code }}-qr.png" target="_blank" class="flex-1 px-3 py-2 bg-violet-600 hover:bg-violet-500 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg text-center transition-colors">Download</a>
                                <button onclick="copyToClipboard('{{ $waiter->waiter_qr_url }}', this)" class="px-3 py-2 glass hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors">Copy Link</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="glass p-4 rounded-xl">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1">Orders</p>
                        <p class="text-xl font-bold text-white">{{ $waiter->orders_count }}</p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button onclick="openViewWaiterModal({{ json_encode($waiter) }})" class="flex-1 glass py-2.5 rounded-xl font-semibold text-white/70 hover:text-white hover:bg-violet-600 transition-all text-sm">View Profile</button>
                    <form action="{{ route('manager.waiters.unlink', $waiter) }}" method="POST" onsubmit="return confirm('Unlink waiter huyu? History (orders, ratings) itabaki. Anaweza kuungwa na restaurant nyingine baadaye.');" class="inline">
                        @csrf
                        <button type="submit" class="p-2.5 glass text-amber-400 rounded-xl hover:bg-amber-500 hover:text-white transition-all" title="Unlink waiter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><line x1="4" x2="20" y1="12" y2="12"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card py-16 text-center rounded-2xl">
                <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white/20">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Hakuna waiters waliounganishwa</h3>
                <p class="text-white/40">Tumia "Link Waiter" hapo juu kwa nambari ya pekee ya waiter.</p>
            </div>
        @endforelse
    </div>

    <!-- History: Link / Unlink -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-white tracking-tight mb-1">Historia ya Waiters (Link / Unlink)</h2>
        <p class="text-sm font-medium text-white/40 uppercase tracking-wider mb-6">Orodha ya waiters uliowaling na kuwatolea kwenye restaurant yako</p>

        @if($assignmentHistory->isNotEmpty())
            <div class="glass-card rounded-2xl overflow-hidden border border-white/10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Waiter</th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Nambari</th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Aina</th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Alilingwa</th>
                                <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Alitolewa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignmentHistory as $a)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="px-4 py-3 font-medium text-white">{{ $a->user?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 font-mono text-sm text-cyan-400">{{ $a->user?->global_waiter_number ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if($a->employment_type === 'temporary')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30">Show-time</span>
                                            @if($a->linked_until)
                                                <span class="text-white/40 text-xs ml-1">mpaka {{ $a->linked_until->format('d/m/Y') }}</span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-white/10 text-white/60 border border-white/10">Muda mrefu</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-white/70">{{ $a->linked_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if($a->unlinked_at)
                                            <span class="text-sm text-white/70">{{ $a->unlinked_at->format('d/m/Y H:i') }}</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-emerald-400 text-sm font-medium">
                                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="glass-card py-12 text-center rounded-2xl border border-white/10">
                <p class="text-white/40">Bado hakuna historia ya link/unlink. Waiters utakao linking au unlink watatokea hapa.</p>
            </div>
        @endif
    </div>

    <!-- View Waiter Modal -->
    <div id="viewWaiterModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-surface-900 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-white/10">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-white tracking-tight">Waiter Profile</h3>
                        <p class="text-sm font-medium text-white/40">Staff details and performance</p>
                    </div>
                    <button onclick="closeViewWaiterModal()" class="p-2 hover:bg-white/10 rounded-xl transition-all text-white/40 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <div class="flex items-center gap-5 mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center font-bold text-3xl text-violet-400 border border-violet-500/20" id="viewWaiterInitial">—</div>
                    <div>
                        <h4 class="text-2xl font-bold text-white" id="viewWaiterName">—</h4>
                        <p class="text-sm text-white/40" id="viewWaiterEmail">—</p>
                        <p class="text-xs font-mono text-cyan-400 mt-1" id="viewWaiterGlobalCode">—</p>
                        <div class="mt-2 inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse mr-2"></span>
                            Active Staff
                        </div>
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mt-2 mb-0.5">Aina ya kuunga</p>
                        <p class="text-xs text-white/70" id="viewWaiterEmployment">—</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1">Service Tag</p>
                        <p class="text-lg font-mono font-bold text-cyan-400" id="viewWaiterCode">—</p>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1">Joined Date</p>
                        <p class="text-lg font-bold text-white" id="viewWaiterJoined">—</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-violet-500/10 p-4 rounded-xl border border-violet-500/20">
                        <p class="text-[10px] font-bold text-violet-300 uppercase tracking-wider mb-1">Total Orders</p>
                        <p class="text-2xl font-bold text-white" id="viewWaiterOrders">0</p>
                    </div>
                </div>

                <button onclick="closeViewWaiterModal()" class="w-full bg-white/10 text-white py-3.5 rounded-xl font-semibold hover:bg-white/20 transition-all">Close Profile</button>
            </div>
        </div>
    </div>

    <script>
        function searchWaiter() {
            const q = document.getElementById('searchCode').value.trim();
            const resultEl = document.getElementById('searchResult');
            const errorEl = document.getElementById('searchError');
            resultEl.classList.add('hidden');
            resultEl.innerHTML = '';
            errorEl.classList.add('hidden');
            if (!q) {
                errorEl.textContent = 'Ingiza nambari ya pekee (TIPTAP-W-xxxxx).';
                errorEl.classList.remove('hidden');
                return;
            }
            resultEl.innerHTML = '<p class="text-white/50 text-sm py-3">Inatafuta…</p>';
            resultEl.classList.remove('hidden');
            fetch('{{ route("manager.waiters.search") }}?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        resultEl.classList.add('hidden');
                        resultEl.innerHTML = '';
                        errorEl.textContent = data.message || 'Waiter hajapatikana.';
                        errorEl.classList.remove('hidden');
                        return;
                    }
                    const w = data.waiter;
                    let html = '<div class="p-4 rounded-xl bg-white/5 border border-white/10">';
                    html += '<p class="font-bold text-white text-lg">' + (w.name || '—') + '</p>';
                    html += '<p class="text-sm text-white/60">' + (w.email || '') + '</p>';
                    html += '<p class="text-sm text-white/60">Simu: ' + (w.phone || '—') + '</p>';
                    if (w.location) html += '<p class="text-sm text-white/60">Mahali: ' + w.location + '</p>';
                    html += '<p class="text-sm font-mono text-cyan-400 mt-2">' + (w.global_waiter_number || '') + '</p>';
                    html += '<p class="text-xs text-white/40 mt-2">Orders: ' + (w.orders_count || 0) + ' · Ratings: ' + (w.feedback_count || 0) + '</p>';
                    if (w.is_linked && w.current_restaurant) {
                        html += '<p class="text-amber-400 text-sm mt-2">Tayari ameunganishwa na: ' + w.current_restaurant + '. Manager wa restaurant ile anafaa kum-unlink kwanza.</p>';
                    } else {
                        var token = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
                        var linkUrl = '{{ url("manager/waiters") }}/' + w.id + '/link';
                        html += '<form action="' + linkUrl + '" method="POST" class="mt-4 space-y-4">';
                        html += '<input type="hidden" name="_token" value="' + token + '">';
                        html += '<p class="text-xs text-white/40 mb-2">Chagua aina ya kuunga: muda mrefu (anabaki mpaka um-unlink) au show-time (muda maalum – weka tarehe ya mwisho).</p>';
                        html += '<div class="flex flex-wrap gap-4 items-end">';
                        html += '<div><label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Aina ya kuunga</label>';
                        html += '<label class="inline-flex items-center gap-2 mr-4"><input type="radio" name="employment_type" value="permanent" checked class="rounded border-white/20" onchange="toggleLinkUntil(this)"> <span class="text-white text-sm">Muda mrefu (Permanent)</span></label>';
                        html += '<label class="inline-flex items-center gap-2"><input type="radio" name="employment_type" value="temporary" class="rounded border-white/20" onchange="toggleLinkUntil(this)"> <span class="text-white text-sm">Muda / Show-time</span></label></div>';
                        html += '<div id="linkUntilWrap" class="hidden"><label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Mpaka tarehe</label>';
                        html += '<input type="date" name="linked_until" id="linkUntilInput" class="px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white text-sm" min="' + new Date().toISOString().slice(0,10) + '"></div>';
                        html += '</div>';
                        html += '<button type="submit" class="px-4 py-2 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">Link Waiter</button>';
                        html += '</form>';
                    }
                    html += '</div>';
                    resultEl.innerHTML = html;
                    resultEl.classList.remove('hidden');
                })
                .catch(() => {
                    resultEl.classList.add('hidden');
                    resultEl.innerHTML = '';
                    errorEl.textContent = 'Hitilafu ya mtandao. Jaribu tena.';
                    errorEl.classList.remove('hidden');
                });
        }

        function openViewWaiterModal(waiter) {
            document.getElementById('viewWaiterName').textContent = waiter.name || '—';
            document.getElementById('viewWaiterEmail').textContent = waiter.email || '—';
            document.getElementById('viewWaiterCode').textContent = waiter.waiter_code || '—';
            document.getElementById('viewWaiterGlobalCode').textContent = waiter.global_waiter_number || '—';
            document.getElementById('viewWaiterInitial').textContent = (waiter.name && waiter.name.charAt(0)) || '—';
            document.getElementById('viewWaiterOrders').textContent = waiter.orders_count ?? 0;
            const date = waiter.created_at ? new Date(waiter.created_at) : null;
            document.getElementById('viewWaiterJoined').textContent = date ? date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '—';
            var emp = (waiter.employment_type === 'temporary' && waiter.linked_until)
                ? 'Show-time · mpaka ' + new Date(waiter.linked_until).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
                : 'Muda mrefu (Permanent)';
            document.getElementById('viewWaiterEmployment').textContent = emp;
            document.getElementById('viewWaiterModal').classList.remove('hidden');
            document.getElementById('viewWaiterModal').classList.add('flex');
        }

        function closeViewWaiterModal() {
            document.getElementById('viewWaiterModal').classList.add('hidden');
            document.getElementById('viewWaiterModal').classList.remove('flex');
        }

        function toggleLinkUntil(radio) {
            var wrap = document.getElementById('linkUntilWrap');
            var input = document.getElementById('linkUntilInput');
            if (radio && radio.value === 'temporary') {
                if (wrap) wrap.classList.remove('hidden');
                if (input) input.setAttribute('required', 'required');
            } else {
                if (wrap) wrap.classList.add('hidden');
                if (input) { input.removeAttribute('required'); input.value = ''; }
            }
        }

        async function copyToClipboard(text, button) {
            try {
                await navigator.clipboard.writeText(text);
                const orig = button.innerHTML;
                button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-emerald-400"><path d="M20 6 9 17l-5-5"/></svg>';
                button.classList.add('bg-emerald-500/20', 'border-emerald-500/30');
                setTimeout(() => { button.innerHTML = orig; button.classList.remove('bg-emerald-500/20', 'border-emerald-500/30'); }, 2000);
            } catch (e) {
                alert('Copy failed');
            }
        }
    </script>
</x-manager-layout>
