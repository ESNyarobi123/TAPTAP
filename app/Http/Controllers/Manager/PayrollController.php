<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\StorePayrollPaymentRequest;
use App\Models\User;
use App\Models\WaiterSalaryPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(Request $request): View
    {
        $restaurantId = Auth::user()->restaurant_id;
        $restaurant = Auth::user()->restaurant;
        $waiters = User::role('waiter')
            ->activeAtRestaurant($restaurantId)
            ->with(['waiterSalaryPayments' => fn ($q) => $q->where('restaurant_id', $restaurantId)])
            ->orderBy('name')
            ->get();

        $requested = $request->string('month')->trim();
        $currentMonth = preg_match('/^\d{4}-\d{2}$/', $requested) ? $requested : now()->format('Y-m');
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $d = now()->subMonths($i);
            $months[] = ['value' => $d->format('Y-m'), 'label' => $d->format('M Y')];
        }

        return view('manager.payroll.index', [
            'waiters' => $waiters,
            'restaurant' => $restaurant,
            'currentMonth' => $currentMonth,
            'months' => $months,
        ]);
    }

    public function store(StorePayrollPaymentRequest $request): RedirectResponse
    {
        $restaurantId = Auth::user()->restaurant_id;
        $waiter = User::role('waiter')->where('id', $request->user_id)->where('restaurant_id', $restaurantId)->firstOrFail();

        $basic = (float) $request->basic_salary;
        $allowances = (float) $request->allowances;
        $paye = (float) $request->paye;
        $nssf = (float) $request->nssf;
        $netPay = $basic + $allowances - $paye - $nssf;

        WaiterSalaryPayment::updateOrCreate(
            [
                'restaurant_id' => $restaurantId,
                'user_id' => $waiter->id,
                'period_month' => $request->period_month,
            ],
            [
                'basic_salary' => $basic,
                'allowances' => $allowances,
                'paye' => $paye,
                'nssf' => $nssf,
                'net_pay' => $netPay,
                'paid_at' => now(),
                'confirmed_by' => Auth::id(),
            ]
        );

        return redirect()->route('manager.payroll.index', ['month' => $request->period_month])->with('success', 'Malipo yamethibitishwa kwa '.$waiter->name.'.');
    }

    public function history(): View
    {
        $restaurantId = Auth::user()->restaurant_id;
        $payments = WaiterSalaryPayment::query()
            ->where('restaurant_id', $restaurantId)
            ->with(['user:id,name,global_waiter_number', 'confirmedByUser:id,name'])
            ->orderByDesc('period_month')
            ->orderBy('user_id')
            ->get();

        $byMonth = $payments->groupBy('period_month')->map(function ($items) {
            return [
                'payments' => $items,
                'total_net' => $items->sum('net_pay'),
                'total_gross' => $items->sum(fn ($p) => (float) $p->basic_salary + (float) $p->allowances),
            ];
        });

        $grandTotal = $payments->sum('net_pay');

        return view('manager.payroll.history', [
            'byMonth' => $byMonth,
            'grandTotal' => $grandTotal,
        ]);
    }
}
