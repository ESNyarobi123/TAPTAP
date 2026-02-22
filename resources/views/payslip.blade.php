<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Salary Slip - {{ $payment->period_label }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a1a; max-width: 420px; margin: 24px auto; padding: 16px; }
        .company { font-size: 11px; color: #444; margin-bottom: 8px; }
        h1 { font-size: 16px; font-weight: bold; margin: 0 0 16px 0; text-align: center; }
        .row { display: table; width: 100%; margin: 4px 0; }
        .label { display: table-cell; }
        .amount { display: table-cell; text-align: right; font-variant-numeric: tabular-nums; }
        .sep { border-bottom: 1px dashed #999; margin: 8px 0; }
        .total { font-weight: bold; margin-top: 4px; }
        .net { font-size: 14px; font-weight: bold; margin-top: 8px; }
    </style>
</head>
<body>
    @if (!empty($restaurantName))
        <div class="company">{{ $restaurantName }}</div>
    @endif
    <h1>EMPLOYEE PAYSLIP - {{ $payment->period_label }}</h1>

    <div class="row">
        <span class="label">Name: {{ $waiterName }}</span>
    </div>
    <div class="row">
        <span class="label">ID: {{ $waiterId }}</span>
    </div>
    <div class="row">
        <span class="label">Position: Waiter</span>
    </div>

    <div class="sep"></div>

    <div class="row">
        <span class="label">Basic Salary</span>
        <span class="amount">{{ number_format($payment->basic_salary) }}</span>
    </div>
    <div class="row">
        <span class="label">Allowances</span>
        <span class="amount">{{ number_format($payment->allowances) }}</span>
    </div>
    <div class="sep"></div>
    <div class="row total">
        <span class="label">Gross Salary</span>
        <span class="amount">{{ number_format($payment->gross_salary) }}</span>
    </div>

    <div class="sep"></div>
    <div class="row"><span class="label">Deductions:</span></div>
    <div class="row">
        <span class="label">PAYE</span>
        <span class="amount">{{ number_format($payment->paye) }}</span>
    </div>
    <div class="row">
        <span class="label">NSSF</span>
        <span class="amount">{{ number_format($payment->nssf) }}</span>
    </div>
    <div class="sep"></div>
    <div class="row total">
        <span class="label">Total Deduction</span>
        <span class="amount">{{ number_format($payment->total_deduction) }}</span>
    </div>

    <div class="sep"></div>
    <div class="row net">
        <span class="label">NET PAY</span>
        <span class="amount">{{ number_format($payment->net_pay) }}</span>
    </div>
    <div class="sep"></div>
</body>
</html>
