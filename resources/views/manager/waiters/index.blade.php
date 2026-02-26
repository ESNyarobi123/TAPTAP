<x-manager-layout>
    <x-slot name="header">Waiters & Staff</x-slot>

    @if (session('success') && !session('order_portal_password_generated'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">{{ session('error') }}</div>
    @endif
    @if (session('order_portal_password_generated'))
        <div class="mb-6 p-4 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-sm">
            {{ session('success') }}
            <p class="mt-2 font-semibold">Password ya Order Portal (onyesha waiter mara moja):</p>
            <p class="mt-1 font-mono text-lg tracking-wider bg-black/20 px-3 py-2 rounded-lg inline-block">{{ session('order_portal_password_generated') }}</p>
            <p class="mt-2 text-white/70">Waiter: <strong>{{ session('order_portal_waiter_name') }}</strong> · Nambari: <code>{{ session('order_portal_waiter_number') }}</code></p>
            <p class="mt-1 text-white/50 text-xs">Login: <a href="{{ $orderPortalLoginUrl ?? route('order-portal.login') }}" class="text-cyan-400 underline" target="_blank">{{ $orderPortalLoginUrl ?? url('/order-portal/login') }}</a></p>
        </div>
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

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Waiters waliounganishwa</h2>
            <p class="text-sm font-medium text-white/40 uppercase tracking-wider">Unlink kuondoa waiter; history inabaki</p>
        </div>
        <a href="{{ route('manager.waiters.history') }}" class="inline-flex items-center gap-2 px-5 py-2.5 glass rounded-xl border border-white/10 text-white/80 hover:text-white hover:bg-white/10 transition-all text-sm font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0 11 18 0z"/></svg>
            Historia ya Waiters (Link / Unlink)
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($waiters as $waiter)
            <div class="glass-card p-6 rounded-2xl card-hover group">
                <div class="flex items-center gap-5 mb-4">
                    @php $waiterPhotoUrl = $waiter->profilePhotoUrl(); @endphp
                    @if($waiterPhotoUrl)
                        <img src="{{ $waiterPhotoUrl }}" alt="{{ $waiter->name }}" loading="lazy" class="w-16 h-16 rounded-2xl object-cover border border-violet-500/20 group-hover:scale-110 transition-transform shrink-0 bg-violet-500/10" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="waiter-fallback-avatar w-16 h-16 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center font-bold text-2xl text-violet-400 border border-violet-500/20 shrink-0 hidden">
                            {{ substr($waiter->name, 0, 1) }}
                        </div>
                    @else
                        <div class="w-16 h-16 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center font-bold text-2xl text-violet-400 border border-violet-500/20 group-hover:scale-110 transition-transform shrink-0">
                            {{ substr($waiter->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h4 class="text-xl font-bold text-white truncate">{{ $waiter->name }}</h4>
                        <p class="text-[11px] font-mono text-cyan-400">{{ $waiter->global_waiter_number ?? '—' }}</p>
                        @if($waiter->is_online)
                            <p class="text-[11px] font-medium text-emerald-400 uppercase tracking-wider flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                Online
                            </p>
                        @else
                            <p class="text-[11px] font-medium text-white/50 uppercase tracking-wider flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 bg-white/40 rounded-full"></span>
                                Offline
                                @if($waiter->last_online_at)
                                    <span class="text-white/40 normal-case font-normal">· mwisho {{ $waiter->last_online_at->diffForHumans() }}</span>
                                @endif
                            </p>
                        @endif
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

                <div class="flex flex-wrap gap-2">
                    @php
                        $waiterForModal = $waiter->only(['id','name','email','waiter_code','global_waiter_number','orders_count','created_at','employment_type','linked_until']);
                        $waiterForModal['profile_photo_url'] = $waiter->profilePhotoUrl();
                        $hasOrderPortal = in_array($waiter->id, $waiterIdsWithOrderPortal ?? []);
                    @endphp
                    <button onclick="openViewWaiterModal({{ json_encode($waiterForModal) }})" class="flex-1 min-w-0 glass py-2.5 rounded-xl font-semibold text-white/70 hover:text-white hover:bg-violet-600 transition-all text-sm">View Profile</button>
                    <form action="{{ route('manager.waiters.generate-order-portal-password', $waiter) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ $hasOrderPortal ? 'glass text-cyan-400 hover:bg-cyan-500/20' : 'bg-cyan-600 hover:bg-cyan-500 text-white' }}" title="{{ $hasOrderPortal ? 'Regenerate Order Portal password' : 'Generate Order Portal password' }}">
                            {{ $hasOrderPortal ? 'Regenerate' : 'Order Portal' }}
                        </button>
                    </form>
                    <form action="{{ route('manager.waiters.unlink', $waiter) }}" method="POST" onsubmit="return confirm('Unlink waiter huyu? History (orders, ratings) itabaki. Password ya Order Portal itaisha. Anaweza kuungwa na restaurant nyingine baadaye.');" class="inline">
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
                    <div class="w-20 h-20 rounded-2xl border border-violet-500/20 overflow-hidden shrink-0" id="viewWaiterPhotoWrap">
                        <img id="viewWaiterPhoto" src="" alt="" class="w-full h-full object-cover hidden">
                        <div id="viewWaiterInitial" class="w-full h-full bg-gradient-to-br from-violet-500/20 to-cyan-500/20 flex items-center justify-center font-bold text-3xl text-violet-400">—</div>
                    </div>
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
                    let html = '<div class="p-4 rounded-xl bg-white/5 border border-white/10 space-y-4">';
                    html += '<div class="flex items-start gap-4">';
                    if (w.profile_photo_url) {
                        html += '<img src="' + w.profile_photo_url + '" alt="" class="w-14 h-14 rounded-xl object-cover border border-violet-500/20 shrink-0">';
                    } else {
                        html += '<div class="w-14 h-14 rounded-xl bg-gradient-to-br from-violet-500/20 to-cyan-500/20 flex items-center justify-center font-bold text-xl text-violet-400 border border-violet-500/20 shrink-0">' + (w.name ? w.name.charAt(0) : '—') + '</div>';
                    }
                    html += '<div class="min-w-0 flex-1"><p class="font-bold text-white text-lg">' + (w.name || '—') + '</p>';
                    html += '<p class="text-sm text-white/60">' + (w.email || '') + '</p>';
                    html += '<p class="text-sm text-white/60">Simu: ' + (w.phone || '—') + '</p>';
                    if (w.location) html += '<p class="text-sm text-white/60">Mahali: ' + w.location + '</p>';
                    html += '<p class="text-sm font-mono text-cyan-400 mt-2">' + (w.global_waiter_number || '') + '</p>';
                    html += '<p class="text-xs text-white/40 mt-2">Orders: ' + (w.orders_count || 0) + ' · Ratings: ' + (w.feedback_count || 0) + '</p></div></div>';

                    if (w.work_history && w.work_history.length > 0) {
                        html += '<div class="pt-3 border-t border-white/10">';
                        html += '<p class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2">Historia ya kazi (alishafanya kazi sehemu flani, muda flani)</p>';
                        html += '<ul class="space-y-2">';
                        w.work_history.forEach(function(h) {
                            const linkedDate = h.linked_at ? new Date(h.linked_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—';
                            const unlinkedDate = h.unlinked_at ? new Date(h.unlinked_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : null;
                            const typeLabel = h.employment_type === 'temporary' ? ' (Show-time)' : ' (Muda mrefu)';
                            if (h.is_active) {
                                html += '<li class="flex items-start gap-2 text-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 shrink-0 animate-pulse"></span><span class="text-white/80"><strong class="text-white">' + (h.restaurant_name || '—') + '</strong>' + typeLabel + ' — Anafanya kazi tangu ' + linkedDate + ' <span class="text-emerald-400 font-medium">(Active)</span></span></li>';
                            } else {
                                html += '<li class="flex items-start gap-2 text-sm"><span class="w-1.5 h-1.5 rounded-full bg-white/30 mt-1.5 shrink-0"></span><span class="text-white/70">Alifanya kazi <strong class="text-white/90">' + (h.restaurant_name || '—') + '</strong>' + typeLabel + ' — ' + linkedDate + ' hadi ' + (unlinkedDate || '—') + '</span></li>';
                            }
                        });
                        html += '</ul></div>';
                    }

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
            var photoEl = document.getElementById('viewWaiterPhoto');
            var initialEl = document.getElementById('viewWaiterInitial');
            if (waiter.profile_photo_url) {
                photoEl.src = waiter.profile_photo_url;
                photoEl.classList.remove('hidden');
                initialEl.classList.add('hidden');
            } else {
                photoEl.classList.add('hidden');
                initialEl.classList.remove('hidden');
                initialEl.textContent = (waiter.name && waiter.name.charAt(0)) || '—';
            }
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
