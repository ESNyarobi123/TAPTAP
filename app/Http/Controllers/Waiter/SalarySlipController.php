<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\WaiterSalaryPayment;
use App\Notifications\SalaryPaymentConfirmed;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SalarySlipController extends Controller
{
    public function index(): View
    {
        Auth::user()->unreadNotifications()
            ->where('type', SalaryPaymentConfirmed::class)
            ->update(['read_at' => now()]);

        $payments = WaiterSalaryPayment::query()
            ->where('user_id', Auth::id())
            ->orderByDesc('period_month')
            ->get();

        return view('waiter.salary-slip.index', ['payments' => $payments]);
    }

    public function show(string $period): View
    {
        $payment = $this->findPaymentForCurrentWaiter($period);
        $this->authorize('view', $payment);

        return $this->payslipView($payment, false);
    }

    public function download(string $period): View
    {
        $payment = $this->findPaymentForCurrentWaiter($period);
        $this->authorize('view', $payment);

        return $this->payslipView($payment, true);
    }

    private function findPaymentForCurrentWaiter(string $period): WaiterSalaryPayment
    {
        return WaiterSalaryPayment::query()
            ->where('user_id', Auth::id())
            ->where('period_month', $period)
            ->with('restaurant')
            ->firstOrFail();
    }

    private function payslipView(WaiterSalaryPayment $payment, bool $autoPrint): View
    {
        $restaurant = $payment->restaurant;
        $waiter = $payment->user;

        return view('payslip', [
            'payment' => $payment,
            'restaurantName' => $restaurant?->name ?? config('app.name'),
            'waiterName' => $waiter?->name ?? '—',
            'waiterId' => $waiter?->global_waiter_number ?? $waiter?->waiter_code ?? ('ID-'.$payment->user_id),
            'forPdf' => false,
            'autoPrint' => $autoPrint,
        ]);
    }
}
