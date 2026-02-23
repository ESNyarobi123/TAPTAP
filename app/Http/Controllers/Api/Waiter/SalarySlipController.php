<?php

namespace App\Http\Controllers\Api\Waiter;

use App\Http\Controllers\Controller;
use App\Models\WaiterSalaryPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SalarySlipController extends Controller
{
    /**
     * List salary slips (payments) for the authenticated waiter.
     */
    public function index(): JsonResponse
    {
        $payments = WaiterSalaryPayment::query()
            ->where('user_id', Auth::id())
            ->orderByDesc('period_month')
            ->get()
            ->map(fn ($p) => [
                'period_month' => $p->period_month,
                'period_label' => $p->period_label,
                'net_pay' => (float) $p->net_pay,
                'view_url' => route('waiter.salary-slip.show', $p->period_month),
                'download_url' => route('waiter.salary-slip.download', $p->period_month),
                'api_download_path' => '/api/waiter/salary-slips/'.$p->period_month.'/download',
            ]);

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    /**
     * Get one salary slip by period (e.g. 2026-02).
     */
    public function show(string $period): JsonResponse
    {
        $payment = WaiterSalaryPayment::query()
            ->where('user_id', Auth::id())
            ->where('period_month', $period)
            ->with('restaurant:id,name')
            ->firstOrFail();

        $this->authorize('view', $payment);

        $waiter = $payment->user;
        $data = [
            'period_month' => $payment->period_month,
            'period_label' => $payment->period_label,
            'restaurant_name' => $payment->restaurant?->name,
            'waiter_name' => $waiter?->name,
            'waiter_id' => $waiter?->global_waiter_number ?? $waiter?->waiter_code ?? ('ID-'.$payment->user_id),
            'basic_salary' => (float) $payment->basic_salary,
            'allowances' => (float) $payment->allowances,
            'gross_salary' => (float) $payment->gross_salary,
            'paye' => (float) $payment->paye,
            'nssf' => (float) $payment->nssf,
            'total_deduction' => (float) $payment->total_deduction,
            'net_pay' => (float) $payment->net_pay,
            'paid_at' => $payment->paid_at?->toIso8601String(),
            'view_url' => route('waiter.salary-slip.show', $payment->period_month),
            'download_url' => route('waiter.salary-slip.download', $payment->period_month),
            'api_download_path' => '/api/waiter/salary-slips/'.$payment->period_month.'/download',
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Download salary slip as PDF (for app view/download).
     * GET /api/waiter/salary-slips/{period}/download
     */
    public function download(string $period): Response
    {
        $payment = WaiterSalaryPayment::query()
            ->where('user_id', Auth::id())
            ->where('period_month', $period)
            ->with(['restaurant:id,name', 'user:id,name,global_waiter_number,waiter_code'])
            ->firstOrFail();

        $this->authorize('view', $payment);

        $restaurant = $payment->restaurant;
        $waiter = $payment->user;

        $pdf = Pdf::loadView('payslip', [
            'payment' => $payment,
            'restaurantName' => $restaurant?->name ?? config('app.name'),
            'waiterName' => $waiter?->name ?? '—',
            'waiterId' => $waiter?->global_waiter_number ?? $waiter?->waiter_code ?? ('ID-'.$payment->user_id),
            'forPdf' => true,
            'autoPrint' => false,
        ]);

        $filename = 'salary-slip-'.$payment->period_month.'.pdf';

        return $pdf->download($filename);
    }
}
