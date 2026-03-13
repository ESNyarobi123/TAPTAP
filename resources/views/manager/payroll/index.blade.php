<x-manager-layout>
    <x-slot name="header">Payroll / Malipo</x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@600;700;800&family=Figtree:wght@400;500;600;700&display=swap');

        .payroll-root { font-family: 'Figtree', sans-serif; }
        .payroll-root h1, .payroll-root h3 { font-family: 'Syne', sans-serif; }
        .mono { font-family: 'DM Mono', monospace; }

        /* Ambient background glow */
        .payroll-bg-glow {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background:
                radial-gradient(ellipse 60% 40% at 10% 20%, rgba(124,58,237,0.08) 0%, transparent 70%),
                radial-gradient(ellipse 50% 35% at 90% 80%, rgba(6,182,212,0.07) 0%, transparent 70%),
                radial-gradient(ellipse 40% 30% at 50% 50%, rgba(16,185,129,0.04) 0%, transparent 70%);
        }

        /* Waiter card */
        .waiter-card {
            position: relative;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            overflow: hidden;
            transition: border-color 0.3s ease, transform 0.2s ease;
        }
        .waiter-card:hover { border-color: rgba(255,255,255,0.15); transform: translateY(-1px); }

        /* Card inner stripe accent */
        .waiter-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
        }

        /* Status pill */
        .pill-paid {
            background: rgba(16,185,129,0.12);
            color: #6ee7b7;
            border: 1px solid rgba(16,185,129,0.25);
        }
        .pill-pending {
            background: rgba(245,158,11,0.12);
            color: #fcd34d;
            border: 1px solid rgba(245,158,11,0.25);
        }

        /* Avatar ring */
        .avatar-ring {
            background: linear-gradient(135deg, rgba(124,58,237,0.4), rgba(6,182,212,0.4));
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px;
        }

        /* Earnings / Deductions panels */
        .fin-panel {
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 20px;
        }
        .fin-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .fin-panel-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: 0.18em; text-transform: uppercase;
        }
        .fin-panel-label.earnings { color: #6ee7b7; }
        .fin-panel-label.deductions { color: #f87171; }
        .fin-panel-dot {
            width: 6px; height: 6px; border-radius: 50%;
        }
        .fin-panel-dot.earnings { background: #10b981; box-shadow: 0 0 8px rgba(16,185,129,0.6); }
        .fin-panel-dot.deductions { background: #ef4444; box-shadow: 0 0 8px rgba(239,68,68,0.6); }

        /* Inputs */
        .fin-input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            color: #fff;
            font-family: 'DM Mono', monospace;
            font-size: 14px;
            padding: 12px 16px;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }
        .fin-input::placeholder { color: rgba(255,255,255,0.2); }
        .fin-input:focus {
            border-color: rgba(124,58,237,0.6);
            background: rgba(124,58,237,0.06);
            box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
        }
        .fin-input-earnings:focus {
            border-color: rgba(16,185,129,0.5);
            background: rgba(16,185,129,0.04);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.08);
        }
        .fin-input-deductions:focus {
            border-color: rgba(239,68,68,0.5);
            background: rgba(239,68,68,0.04);
            box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
        }

        /* Net summary panel */
        .net-panel {
            background: rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 16px 18px;
        }
        .net-row { display: flex; align-items: center; justify-content: space-between; }
        .net-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 10px 0; }
        .net-total { font-family: 'DM Mono', monospace; font-size: 18px; font-weight: 600; color: #fff; }

        /* CTA Buttons */
        .btn-confirm {
            width: 100%;
            padding: 14px 20px;
            border-radius: 14px;
            font-family: 'Syne', sans-serif;
            font-size: 13px; font-weight: 700;
            letter-spacing: 0.04em;
            background: linear-gradient(135deg, #7c3aed, #0891b2);
            color: #fff;
            border: none; cursor: pointer;
            position: relative; overflow: hidden;
            transition: transform 0.15s ease, opacity 0.15s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 20px rgba(124,58,237,0.3);
        }
        .btn-confirm::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0; transition: opacity 0.2s;
        }
        .btn-confirm:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(124,58,237,0.4); }
        .btn-confirm:hover:not(:disabled)::before { opacity: 1; }
        .btn-confirm:active:not(:disabled) { transform: translateY(0); }
        .btn-confirm:disabled { opacity: 0.5; cursor: not-allowed; }

        .btn-update {
            width: 100%;
            padding: 14px 20px;
            border-radius: 14px;
            font-family: 'Syne', sans-serif;
            font-size: 13px; font-weight: 700;
            letter-spacing: 0.04em;
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.12); cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
        }
        .btn-update:hover:not(:disabled) { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.2); }

        /* Net preview header box */
        .preview-box {
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 14px 18px;
            min-width: 180px;
        }
        .preview-box .amount {
            font-family: 'DM Mono', monospace;
            font-size: 22px; font-weight: 500; color: #fff;
            margin-top: 4px;
        }

        /* Alert boxes */
        .alert-success {
            padding: 16px 20px; border-radius: 16px;
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            color: #6ee7b7; font-size: 14px;
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 24px;
        }
        .alert-error {
            padding: 16px 20px; border-radius: 16px;
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #fca5a5; font-size: 14px;
            margin-bottom: 24px;
        }

        /* Month select */
        .month-select {
            padding: 10px 16px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: #fff;
            font-family: 'Figtree', sans-serif;
            font-size: 14px; font-weight: 500;
            outline: none; cursor: pointer;
            min-width: 170px;
            transition: border-color 0.2s;
        }
        .month-select:focus { border-color: rgba(124,58,237,0.5); }

        /* History button */
        .btn-history {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: rgba(255,255,255,0.8);
            font-family: 'Figtree', sans-serif;
            font-size: 13px; font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s, color 0.2s;
        }
        .btn-history:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: #fff; }

        /* Stat chips */
        .stat-chip {
            padding: 10px 18px; border-radius: 12px;
            display: flex; align-items: center; gap: 10px;
            font-size: 13px; font-weight: 600;
        }
        .stat-chip-neutral { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); }
        .stat-chip-paid { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #6ee7b7; }
        .stat-chip-pending { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); color: #fcd34d; }
        .stat-dot { width: 7px; height: 7px; border-radius: 50%; }

        /* Empty state */
        .empty-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 24px;
            padding: 80px 40px;
            text-align: center;
        }
        .empty-icon-wrap {
            width: 80px; height: 80px; border-radius: 20px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .btn-primary-cta {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #7c3aed, #0891b2);
            color: #fff; border-radius: 14px;
            font-family: 'Syne', sans-serif;
            font-size: 14px; font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(124,58,237,0.3);
            transition: box-shadow 0.2s, transform 0.15s;
        }
        .btn-primary-cta:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(124,58,237,0.4); }

        /* Field label */
        .field-label {
            display: block;
            font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: rgba(255,255,255,0.45);
            margin-bottom: 8px;
        }

        /* Confirmed net badge */
        .confirmed-net {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 12px;
            padding: 12px 16px;
            color: #6ee7b7;
            font-family: 'DM Mono', monospace;
            font-size: 13px;
        }

        /* Separator line */
        .section-sep { height: 1px; background: rgba(255,255,255,0.05); margin: 0 -8px; }

        /* Next step hint */
        .hint-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 12px;
        }
    </style>

    <div class="payroll-root" style="position:relative; z-index:1;">
        <div class="payroll-bg-glow"></div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert-success">
                <span style="flex-shrink:0; width:36px; height:36px; border-radius:10px; background:rgba(16,185,129,0.2); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-error">
                <ul style="list-style:disc; padding-left:18px; margin:0; line-height:1.8;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Page header --}}
        <div style="margin-bottom:32px;">
            <div style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:20px;">
                <div>
                    <h1 style="font-size:32px; font-weight:800; color:#fff; letter-spacing:-0.5px; margin:0 0 6px;">Payroll</h1>
                    <p style="color:rgba(255,255,255,0.45); font-size:14px; margin:0; max-width:480px; line-height:1.6;">Thibitisha malipo ya mshahara kwa kila waiter. Chagua mwezi, jaza kiasi, kisha bofya <strong style="color:rgba(255,255,255,0.7);">Thibitisha</strong>.</p>
                </div>
                <div style="display:flex; flex-wrap:wrap; align-items:center; gap:12px;">
                    <form method="GET" action="{{ route('manager.payroll.index') }}" style="display:flex; align-items:center; gap:10px;">
                        <label for="month" style="font-size:10px; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:rgba(255,255,255,0.4);">Mwezi</label>
                        <select name="month" id="month" onchange="this.form.submit()" class="month-select">
                            @foreach ($months as $m)
                                <option value="{{ $m['value'] }}" {{ $m['value'] === $currentMonth ? 'selected' : '' }}>{{ $m['label'] }}</option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route('manager.payroll.history') }}" class="btn-history">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Historia ya Malipo
                    </a>
                </div>
            </div>

            @if (!$waiters->isEmpty())
                @php
                    $paidCount = $waiters->filter(fn ($w) => $w->waiterSalaryPayments->firstWhere('period_month', $currentMonth))->count();
                    $pendingCount = $waiters->count() - $paidCount;
                @endphp
                <div style="margin-top:20px; display:flex; flex-wrap:wrap; gap:10px;">
                    <div class="stat-chip stat-chip-neutral">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:rgba(255,255,255,0.4)"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span style="color:rgba(255,255,255,0.55);">{{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->format('F Y') }}</span>
                    </div>
                    <div class="stat-chip stat-chip-paid">
                        <span class="stat-dot" style="background:#10b981; box-shadow:0 0 6px rgba(16,185,129,0.7);"></span>
                        {{ $paidCount }} amelipwa
                    </div>
                    @if ($pendingCount > 0)
                        <div class="stat-chip stat-chip-pending">
                            <span class="stat-dot" style="background:#f59e0b; box-shadow:0 0 6px rgba(245,158,11,0.7);"></span>
                            {{ $pendingCount }} hajalipwa
                        </div>
                    @endif
                    <div class="stat-chip stat-chip-neutral" style="color:rgba(255,255,255,0.5);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        {{ $waiters->count() }} wote
                    </div>
                </div>
            @endif
        </div>

        {{-- Empty state --}}
        @if ($waiters->isEmpty())
            <div class="empty-card">
                <div class="empty-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:rgba(255,255,255,0.25)">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3 style="font-size:20px; font-weight:700; color:#fff; margin:0 0 8px;">Hakuna waiters waliounganishwa</h3>
                <p style="color:rgba(255,255,255,0.4); font-size:14px; max-width:340px; margin:0 auto 24px; line-height:1.6;">Unganisha waiters kwenye Waiters &amp; Staff kwanza, kisha utaweza kuthibitisha malipo yao hapa.</p>
                <a href="{{ route('manager.waiters.index') }}" class="btn-primary-cta">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Nenda kwa Waiters &amp; Staff
                </a>
            </div>

        @else
            <div style="display:flex; flex-direction:column; gap:20px;">
                @foreach ($waiters as $waiter)
                    @php
                        $payment = $waiter->waiterSalaryPayments->firstWhere('period_month', $currentMonth);
                        $basicValue = old('user_id') == $waiter->id ? (int) old('basic_salary', 0) : (int) ($payment?->basic_salary ?? 0);
                        $allowancesValue = old('user_id') == $waiter->id ? (int) old('allowances', 0) : (int) ($payment?->allowances ?? 0);
                        $payeValue = old('user_id') == $waiter->id ? (int) old('paye', 0) : (int) ($payment?->paye ?? 0);
                        $nssfValue = old('user_id') == $waiter->id ? (int) old('nssf', 0) : (int) ($payment?->nssf ?? 0);
                        $netPreview = max(($basicValue + $allowancesValue) - ($payeValue + $nssfValue), 0);
                        $isPaid = (bool) $payment;
                    @endphp

                    <div class="waiter-card">
                        <div style="padding:28px 28px 0;">

                            {{-- Card header: identity + status + net preview --}}
                            <div style="display:flex; flex-wrap:wrap; align-items:flex-start; justify-content:space-between; gap:20px; margin-bottom:24px;">
                                {{-- Identity --}}
                                <div style="display:flex; align-items:center; gap:16px;">
                                    <div class="avatar-ring" style="width:56px; height:56px; display:flex; align-items:center; justify-content:center; font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#fff; flex-shrink:0;">
                                        {{ strtoupper(substr($waiter->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                                            <h3 style="font-size:18px; font-weight:700; color:#fff; margin:0; letter-spacing:-0.2px;">{{ $waiter->name }}</h3>
                                            <span class="pill-{{ $isPaid ? 'paid' : 'pending' }}" style="font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; padding:4px 10px; border-radius:20px;">
                                                {{ $isPaid ? '✓ Amelipwa' : '○ Bado' }}
                                            </span>
                                        </div>
                                        <p class="mono" style="font-size:12px; color:rgba(6,182,212,0.7); margin:4px 0 0;">GW-{{ $waiter->global_waiter_number ?? '—' }}</p>
                                    </div>
                                </div>

                                {{-- Net preview + confirmed net --}}
                                <div style="display:flex; flex-wrap:wrap; align-items:center; gap:12px;">
                                    <div class="preview-box">
                                        <p style="font-size:10px; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:rgba(255,255,255,0.4); margin:0;">Net Preview</p>
                                        <p class="amount">{{ number_format($netPreview) }}</p>
                                        <p style="font-size:11px; color:rgba(255,255,255,0.3); margin:2px 0 0;" class="mono">TZS</p>
                                    </div>
                                    @if ($isPaid)
                                        <div class="confirmed-net">
                                            <p style="font-size:10px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:rgba(16,185,129,0.6); margin:0 0 4px;">Imethibitishwa</p>
                                            <p style="margin:0; font-size:15px;">{{ number_format($payment->net_pay) }} <span style="font-size:11px; color:rgba(16,185,129,0.5);">TZS</span></p>
                                        </div>
                                    @else
                                        <div class="hint-box">
                                            <p style="font-weight:700; color:rgba(255,255,255,0.6); margin:0 0 3px; font-size:12px;">Hatua inayofuata</p>
                                            <p style="color:rgba(255,255,255,0.35); margin:0; font-size:11px; line-height:1.5;">Jaza kiasi hapa chini, kisha bonyeza Thibitisha.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="section-sep" style="margin:0 28px;"></div>

                        {{-- Form --}}
                        <form action="{{ route('manager.payroll.store') }}" method="POST" style="padding:20px 28px 28px;">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $waiter->id }}">
                            <input type="hidden" name="period_month" value="{{ $currentMonth }}">

                            <div style="display:grid; grid-template-columns:1fr 1fr auto; gap:16px; align-items:start;">

                                {{-- Earnings --}}
                                <div class="fin-panel">
                                    <div class="fin-panel-header">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <span class="fin-panel-dot earnings"></span>
                                            <span class="fin-panel-label earnings">Earnings</span>
                                        </div>
                                        <span style="font-size:10px; font-weight:600; color:rgba(255,255,255,0.2); font-family:'DM Mono',monospace;">TZS</span>
                                    </div>
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                        <div>
                                            <label class="field-label">Basic Salary</label>
                                            <input type="number" name="basic_salary" value="{{ $basicValue }}" min="0" step="1" placeholder="0" class="fin-input fin-input-earnings">
                                        </div>
                                        <div>
                                            <label class="field-label">Allowances</label>
                                            <input type="number" name="allowances" value="{{ $allowancesValue }}" min="0" step="1" placeholder="0" class="fin-input fin-input-earnings">
                                        </div>
                                    </div>
                                    <div style="margin-top:14px; padding-top:12px; border-top:1px solid rgba(255,255,255,0.05); display:flex; justify-content:space-between; align-items:center;">
                                        <span style="font-size:11px; color:rgba(255,255,255,0.35);">Jumla ya Earnings</span>
                                        <span class="mono" style="font-size:13px; color:#6ee7b7;" id="total-earn-{{ $waiter->id }}">{{ number_format($basicValue + $allowancesValue) }}</span>
                                    </div>
                                </div>

                                {{-- Deductions --}}
                                <div class="fin-panel">
                                    <div class="fin-panel-header">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <span class="fin-panel-dot deductions"></span>
                                            <span class="fin-panel-label deductions">Deductions</span>
                                        </div>
                                        <span style="font-size:10px; font-weight:600; color:rgba(255,255,255,0.2); font-family:'DM Mono',monospace;">TZS</span>
                                    </div>
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                        <div>
                                            <label class="field-label">PAYE</label>
                                            <input type="number" name="paye" value="{{ $payeValue }}" min="0" step="1" placeholder="0" class="fin-input fin-input-deductions">
                                        </div>
                                        <div>
                                            <label class="field-label">NSSF</label>
                                            <input type="number" name="nssf" value="{{ $nssfValue }}" min="0" step="1" placeholder="0" class="fin-input fin-input-deductions">
                                        </div>
                                    </div>
                                    <div style="margin-top:14px; padding-top:12px; border-top:1px solid rgba(255,255,255,0.05); display:flex; justify-content:space-between; align-items:center;">
                                        <span style="font-size:11px; color:rgba(255,255,255,0.35);">Jumla ya Deductions</span>
                                        <span class="mono" style="font-size:13px; color:#f87171;" id="total-deduct-{{ $waiter->id }}">{{ number_format($payeValue + $nssfValue) }}</span>
                                    </div>
                                </div>

                                {{-- Net summary + CTA --}}
                                <div style="min-width:180px; display:flex; flex-direction:column; gap:12px;">
                                    <div class="net-panel">
                                        <p style="font-size:10px; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:rgba(255,255,255,0.35); margin:0 0 12px;">Muhtasari</p>
                                        <div class="net-row">
                                            <span style="font-size:12px; color:rgba(255,255,255,0.5);">Earnings</span>
                                            <span class="mono" style="font-size:12px; color:#6ee7b7;" id="sum-earn-{{ $waiter->id }}">{{ number_format($basicValue + $allowancesValue) }}</span>
                                        </div>
                                        <div class="net-row" style="margin-top:6px;">
                                            <span style="font-size:12px; color:rgba(255,255,255,0.5);">Deductions</span>
                                            <span class="mono" style="font-size:12px; color:#f87171;" id="sum-deduct-{{ $waiter->id }}">− {{ number_format($payeValue + $nssfValue) }}</span>
                                        </div>
                                        <div class="net-divider"></div>
                                        <div class="net-row">
                                            <span style="font-size:13px; font-weight:700; color:#fff;">Net Pay</span>
                                            <span class="net-total" id="net-display-{{ $waiter->id }}">{{ number_format($netPreview) }}</span>
                                        </div>
                                    </div>
                                    <button type="submit" class="{{ $isPaid ? 'btn-update' : 'btn-confirm' }}" data-paid="{{ $isPaid ? '1' : '0' }}">
                                        {{ $isPaid ? 'Sasisha Malipo' : 'Thibitisha Nimewalipa' }}
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <p style="margin-top:20px; font-size:13px; color:rgba(255,255,255,0.3);">
                Mwezi unaotumika: <strong style="color:rgba(255,255,255,0.5);">{{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->format('F Y') }}</strong>. Badilisha kipindi kwa kuchagua mwezi hapo juu.
            </p>
        @endif
    </div>

    <script>
    (function() {
        // Live net calculator per waiter card
        document.querySelectorAll('.waiter-card').forEach(function(card) {
            var form = card.querySelector('form[action*="payroll"]');
            if (!form) return;

            var uid = form.querySelector('[name="user_id"]')?.value;
            if (!uid) return;

            var inputs = {
                basic: form.querySelector('[name="basic_salary"]'),
                allowances: form.querySelector('[name="allowances"]'),
                paye: form.querySelector('[name="paye"]'),
                nssf: form.querySelector('[name="nssf"]'),
            };

            function fmt(n) {
                return n.toLocaleString('en-US');
            }

            function recalc() {
                var basic = parseInt(inputs.basic?.value || 0) || 0;
                var allow = parseInt(inputs.allowances?.value || 0) || 0;
                var paye  = parseInt(inputs.paye?.value  || 0) || 0;
                var nssf  = parseInt(inputs.nssf?.value  || 0) || 0;
                var earn  = basic + allow;
                var deduct = paye + nssf;
                var net   = Math.max(earn - deduct, 0);

                var elEarn   = document.getElementById('total-earn-' + uid);
                var elDeduct = document.getElementById('total-deduct-' + uid);
                var elSumEarn   = document.getElementById('sum-earn-' + uid);
                var elSumDeduct = document.getElementById('sum-deduct-' + uid);
                var elNet    = document.getElementById('net-display-' + uid);

                if (elEarn)      elEarn.textContent      = fmt(earn);
                if (elDeduct)    elDeduct.textContent     = fmt(deduct);
                if (elSumEarn)   elSumEarn.textContent    = fmt(earn);
                if (elSumDeduct) elSumDeduct.textContent  = '− ' + fmt(deduct);
                if (elNet)       elNet.textContent        = fmt(net);

                // Also update the preview-box amount (first .amount in the header)
                var previewAmt = card.querySelector('.amount');
                if (previewAmt) previewAmt.textContent = fmt(net);
            }

            Object.values(inputs).forEach(function(inp) {
                if (inp) inp.addEventListener('input', recalc);
            });
        });

        // Disable submit on click to prevent double submit
        document.querySelectorAll('form[action*="payroll"]').forEach(function(form) {
            form.addEventListener('submit', function() {
                var btn = form.querySelector('button[type="submit"]');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    btn.textContent = 'Inaendesha…';
                }
            });
        });
    })();
    </script>
</x-manager-layout>