<x-manager-layout>
    <x-slot name="header">Payroll / Malipo</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Payroll – Waiters</h2>
            <p class="text-sm font-medium text-white/40 uppercase tracking-wider">Thibitisha malipo ya mshahara kwa kila waiter baada ya kuwalipa cash</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('manager.payroll.index') }}" class="flex items-center gap-2">
                <label for="month" class="text-[10px] font-bold uppercase text-white/40">Mwezi</label>
                <select name="month" id="month" onchange="this.form.submit()" class="px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-violet-500">
                    @foreach ($months as $m)
                        <option value="{{ $m['value'] }}" {{ $m['value'] === $currentMonth ? 'selected' : '' }}>{{ $m['label'] }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('manager.payroll.history') }}" class="inline-flex items-center gap-2 px-5 py-2.5 glass rounded-xl border border-white/10 text-white/80 hover:text-white hover:bg-white/10 transition-all text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0 11 18 0z"/></svg>
                Historia ya Malipo
            </a>
        </div>
    </div>

    @if ($waiters->isEmpty())
        <div class="glass-card py-16 text-center rounded-2xl border border-white/10">
            <p class="text-white/60">Hakuna waiters waliounganishwa. Unganisha waiters kwenye <a href="{{ route('manager.waiters.index') }}" class="text-cyan-400 hover:underline">Waiters & Staff</a> kwanza.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($waiters as $waiter)
                @php
                    $payment = $waiter->waiterSalaryPayments->firstWhere('period_month', $currentMonth);
                @endphp
                <div class="glass-card rounded-2xl p-6 border border-white/10">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center font-bold text-lg text-violet-400 shrink-0">
                                {{ substr($waiter->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">{{ $waiter->name }}</h3>
                                <p class="text-xs font-mono text-cyan-400">{{ $waiter->global_waiter_number ?? '—' }}</p>
                                @if ($payment)
                                    <span class="inline-flex items-center gap-1.5 mt-2 text-emerald-400 text-sm font-medium">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                        Amelipwa {{ $payment->period_label }} – Net {{ number_format($payment->net_pay) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="min-w-[280px]">
                            <form action="{{ route('manager.payroll.store') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $waiter->id }}">
                                <input type="hidden" name="period_month" value="{{ $currentMonth }}">
                                <div class="grid grid-cols-2 gap-2 w-full">
                                    <div>
                                        <label class="text-[10px] font-bold uppercase text-white/40 block mb-0.5">Basic</label>
                                        <input type="number" name="basic_salary" value="{{ old('user_id') == $waiter->id ? old('basic_salary') : ($payment?->basic_salary ?? 0) }}" min="0" step="1"
                                               class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold uppercase text-white/40 block mb-0.5">Allowances</label>
                                        <input type="number" name="allowances" value="{{ old('user_id') == $waiter->id ? old('allowances') : ($payment?->allowances ?? 0) }}" min="0" step="1"
                                               class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold uppercase text-white/40 block mb-0.5">PAYE</label>
                                        <input type="number" name="paye" value="{{ old('user_id') == $waiter->id ? old('paye') : ($payment?->paye ?? 0) }}" min="0" step="1"
                                               class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white text-sm">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold uppercase text-white/40 block mb-0.5">NSSF</label>
                                        <input type="number" name="nssf" value="{{ old('user_id') == $waiter->id ? old('nssf') : ($payment?->nssf ?? 0) }}" min="0" step="1"
                                               class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white text-sm">
                                    </div>
                                </div>
                                <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-violet-500/25 transition-all text-sm">
                                    {{ $payment ? 'Thibitisha tena (update)' : 'Thibitisha Nimewalipa' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 p-4 rounded-xl bg-white/5 border border-white/10 text-sm text-white/60">
            <strong class="text-white/80">Mwezi unaotumika:</strong> {{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->format('F Y') }}.
            Unaweza kubadilisha kipindi kwenye Historia ya Malipo.
        </div>
    @endif
</x-manager-layout>
