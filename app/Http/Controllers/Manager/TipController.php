<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TipController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $restaurantId = Auth::user()->restaurant_id;

        $totalTipsToday = Tip::whereDate('created_at', $today)->sum('amount');
        $avgTip = Tip::whereDate('created_at', $today)->avg('amount') ?? 0;
        
        $topWaiter = User::role('waiter')
            ->where('restaurant_id', $restaurantId)
            ->withSum(['tips' => function($query) use ($today) {
                $query->whereDate('created_at', $today);
            }], 'amount')
            ->orderByDesc('tips_sum_amount')
            ->first();

        $waiterPerformance = User::role('waiter')
            ->where('restaurant_id', $restaurantId)
            ->withCount('orders')
            ->withSum('tips', 'amount')
            ->get();

        return view('manager.tips.index', compact('totalTipsToday', 'avgTip', 'topWaiter', 'waiterPerformance'));
    }
}
