<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\WaiterSalaryPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalarySlipController extends Controller
{
    public function index(): View
    {
        $payments = WaiterSalaryPayment::query()
            ->where('user_id', Auth::id())
            ->orderByDesc('period_month')
            ->get();

        return view('waiter.salary-slip.index', ['payments' => $payments]);
    }

    public function show(string $period): Response|StreamedResponse
    {
        $payment = $this->findPaymentForCurrentWaiter($period);

        return $this->pdfResponse($payment, false);
    }

    public function download(string $period): Response|StreamedResponse
    {
        $payment = $this->findPaymentForCurrentWaiter($period);

        return $this->pdfResponse($payment, true);
    }

    private function findPaymentForCurrentWaiter(string $period): WaiterSalaryPayment
    {
        $payment = WaiterSalaryPayment::query()
            ->where('user_id', Auth::id())
            ->where('period_month', $period)
            ->with('restaurant')
            ->firstOrFail();

        return $payment;
    }

    private function pdfResponse(WaiterSalaryPayment $payment, bool $download): Response|StreamedResponse
    {
        $restaurant = $payment->restaurant;
        $waiter = $payment->user;

        $pdf = Pdf::loadView('payslip', [
            'payment' => $payment,
            'restaurantName' => $restaurant?->name ?? config('app.name'),
            'waiterName' => $waiter?->name ?? '—',
            'waiterId' => $waiter?->global_waiter_number ?? $waiter?->waiter_code ?? ('ID-'.$payment->user_id),
        ]);

        $filename = 'payslip-'.str_replace('-', '', $payment->period_month).'-'.preg_replace('/[^a-z0-9]+/i', '-', $waiter?->name ?? 'waiter').'.pdf';

        if ($download) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}
