<x-manager-layout>
    <x-slot name="header">Historia ya Malipo</x-slot>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Historia ya Malipo</h2>
            <p class="text-sm font-medium text-white/40 uppercase tracking-wider mt-0.5">Malipo yote uliyothibitisha na jumla ya mshahara kwa kila mwezi</p>
        </div>
        <a href="{{ route('manager.payroll.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 glass rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all text-sm font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Rudi kwa Payroll
        </a>
    </div>

    @if ($byMonth->isEmpty())
        <div class="glass-card py-16 text-center rounded-2xl border border-white/10">
            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white/20">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0 11 18 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Hakuna malipo bado</h3>
            <p class="text-white/40">Thibitisha malipo kwenye <a href="{{ route('manager.payroll.index') }}" class="text-cyan-400 hover:underline">Payroll</a>.</p>
        </div>
    @else
        <div class="glass-card rounded-2xl p-6 mb-6 border border-white/10">
            <p class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1">Jumla ya malipo yote (Net Pay)</p>
            <p class="text-2xl font-bold text-white">{{ number_format($grandTotal) }}</p>
        </div>

        <div class="space-y-8">
            @foreach ($byMonth as $period => $data)
                @php
                    $parts = explode('-', $period);
                    $label = count($parts) === 2 ? \Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y') : $period;
                @endphp
                <div class="glass-card rounded-2xl overflow-hidden border border-white/10">
                    <div class="px-6 py-4 border-b border-white/10 flex flex-wrap items-center justify-between gap-4">
                        <h3 class="text-lg font-bold text-white">{{ $label }}</h3>
                        <div class="flex gap-6 text-sm">
                            <span class="text-white/50">Gross: <strong class="text-white">{{ number_format($data['total_gross']) }}</strong></span>
                            <span class="text-white/50">Net: <strong class="text-emerald-400">{{ number_format($data['total_net']) }}</strong></span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Waiter</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">ID</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50 text-right">Basic</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50 text-right">Allowances</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50 text-right">PAYE</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50 text-right">NSSF</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50 text-right">Net Pay</th>
                                    <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Alipolipwa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['payments'] as $p)
                                    <tr class="border-b border-white/5 hover:bg-white/5">
                                        <td class="px-4 py-3 font-medium text-white">{{ $p->user?->name ?? '—' }}</td>
                                        <td class="px-4 py-3 font-mono text-sm text-cyan-400">{{ $p->user?->global_waiter_number ?? '—' }}</td>
                                        <td class="px-4 py-3 text-right text-white/80">{{ number_format($p->basic_salary) }}</td>
                                        <td class="px-4 py-3 text-right text-white/80">{{ number_format($p->allowances) }}</td>
                                        <td class="px-4 py-3 text-right text-white/80">{{ number_format($p->paye) }}</td>
                                        <td class="px-4 py-3 text-right text-white/80">{{ number_format($p->nssf) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-emerald-400">{{ number_format($p->net_pay) }}</td>
                                        <td class="px-4 py-3 text-sm text-white/60">{{ $p->paid_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-manager-layout>
