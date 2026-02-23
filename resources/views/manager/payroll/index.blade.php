<x-manager-layout>
    <x-slot name="header">Payroll / Malipo</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3 shadow-lg shadow-emerald-500/5">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Hero + Month selector --}}
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Payroll</h1>
                <p class="mt-1 text-white/50 max-w-xl">Thibitisha malipo ya mshahara kwa kila waiter baada ya kuwalipa cash. Chagua mwezi, jaza kiasi, kisha bofya Thibitisha.</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('manager.payroll.index') }}" class="flex items-center gap-3">
                    <label for="month" class="text-xs font-bold uppercase tracking-wider text-white/50">Mwezi</label>
                    <select name="month" id="month" onchange="this.form.submit()" class="px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white font-medium focus:ring-2 focus:ring-violet-500 focus:border-violet-500/50 transition-all min-w-[160px]">
                        @foreach ($months as $m)
                            <option value="{{ $m['value'] }}" {{ $m['value'] === $currentMonth ? 'selected' : '' }}>{{ $m['label'] }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('manager.payroll.history') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-white/10 bg-white/5 text-white/90 hover:bg-white/10 hover:text-white transition-all text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0 11 18 0z"/></svg>
                    Historia ya Malipo
                </a>
            </div>
        </div>
        @if (!$waiters->isEmpty())
            @php
                $paidCount = $waiters->filter(fn ($w) => $w->waiterSalaryPayments->firstWhere('period_month', $currentMonth))->count();
                $pendingCount = $waiters->count() - $paidCount;
            @endphp
            <div class="mt-6 flex flex-wrap gap-4">
                <div class="px-5 py-3 rounded-xl bg-white/5 border border-white/10 flex items-center gap-3">
                    <span class="text-white/50 text-sm font-medium">Mwezi:</span>
                    <span class="text-white font-semibold">{{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->format('F Y') }}</span>
                </div>
                <div class="px-5 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span class="text-emerald-400 text-sm font-semibold">{{ $paidCount }} amelipwa</span>
                </div>
                @if ($pendingCount > 0)
                    <div class="px-5 py-3 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span class="text-amber-400 text-sm font-semibold">{{ $pendingCount }} hajalipwa</span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if ($waiters->isEmpty())
        <div class="glass-card py-20 text-center rounded-2xl border border-white/10">
            <div class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-white/30">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Hakuna waiters waliounganishwa</h3>
            <p class="text-white/50 max-w-sm mx-auto mb-6">Unganisha waiters kwenye Waiters & Staff kwanza, kisha utaweza kuthibitisha malipo yao hapa.</p>
            <a href="{{ route('manager.waiters.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Nenda kwa Waiters & Staff
            </a>
        </div>
    @else
        <div class="space-y-5">
            @foreach ($waiters as $waiter)
                @php
                    $payment = $waiter->waiterSalaryPayments->firstWhere('period_month', $currentMonth);
                @endphp
                <div class="glass-card rounded-2xl border border-white/10 overflow-hidden transition-all hover:border-white/15">
                    <div class="p-6 lg:p-8">
                        <div class="flex flex-col lg:flex-row lg:items-stretch gap-6 lg:gap-8">
                            {{-- Waiter info --}}
                            <div class="flex items-center gap-4 shrink-0">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500/20 to-cyan-500/20 flex items-center justify-center font-bold text-xl text-violet-400 border border-white/10 shrink-0">
                                    {{ substr($waiter->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $waiter->name }}</h3>
                                    <p class="text-sm font-mono text-cyan-400/90">{{ $waiter->global_waiter_number ?? '—' }}</p>
                                    @if ($payment)
                                        <div class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                            <span class="text-emerald-400 text-sm font-semibold">Amelipwa – Net {{ number_format($payment->net_pay) }}</span>
                                        </div>
                                    @else
                                        <div class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20">
                                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                            <span class="text-amber-400 text-sm font-semibold">Hajalipwa</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Form: Earnings + Deductions + CTA --}}
                            <div class="flex-1 min-w-0">
                                <form action="{{ route('manager.payroll.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-12 gap-4 items-end">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $waiter->id }}">
                                    <input type="hidden" name="period_month" value="{{ $currentMonth }}">

                                    <div class="sm:col-span-2 xl:col-span-5 space-y-3 p-4 rounded-xl bg-white/5 border border-white/10">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-white/40">Earnings</p>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="text-[10px] font-bold uppercase text-white/40 block mb-1">Basic</label>
                                                <input type="number" name="basic_salary" value="{{ old('user_id') == $waiter->id ? old('basic_salary') : ($payment?->basic_salary ?? 0) }}" min="0" step="1" placeholder="0"
                                                       class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/20 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold uppercase text-white/40 block mb-1">Allowances</label>
                                                <input type="number" name="allowances" value="{{ old('user_id') == $waiter->id ? old('allowances') : ($payment?->allowances ?? 0) }}" min="0" step="1" placeholder="0"
                                                       class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/20 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2 xl:col-span-5 space-y-3 p-4 rounded-xl bg-white/5 border border-white/10">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-white/40">Deductions</p>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="text-[10px] font-bold uppercase text-white/40 block mb-1">PAYE</label>
                                                <input type="number" name="paye" value="{{ old('user_id') == $waiter->id ? old('paye') : ($payment?->paye ?? 0) }}" min="0" step="1" placeholder="0"
                                                       class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/20 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold uppercase text-white/40 block mb-1">NSSF</label>
                                                <input type="number" name="nssf" value="{{ old('user_id') == $waiter->id ? old('nssf') : ($payment?->nssf ?? 0) }}" min="0" step="1" placeholder="0"
                                                       class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/20 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2 xl:col-span-2 flex flex-col justify-end">
                                        <button type="submit" class="w-full px-5 py-3 rounded-xl font-semibold text-sm transition-all {{ $payment ? 'bg-white/10 hover:bg-white/15 text-white border border-white/10' : 'bg-gradient-to-r from-violet-600 to-cyan-600 text-white hover:shadow-lg hover:shadow-violet-500/25' }}">
                                            {{ $payment ? 'Update' : 'Thibitisha Nimewalipa' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="mt-6 text-sm text-white/40">
            Mwezi unaotumika: <strong class="text-white/60">{{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->format('F Y') }}</strong>. Badilisha kipindi kwa kuchagua mwezi hapo juu.
        </p>
    @endif
</x-manager-layout>
