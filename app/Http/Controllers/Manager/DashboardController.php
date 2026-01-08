<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Feedback;
use App\Models\Tip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $restaurantId = Auth::user()->restaurant_id;
        $today = Carbon::today();

        // Stats
        $totalOrdersToday = Order::whereDate('created_at', $today)->count();
        $revenueToday = Order::whereDate('created_at', $today)->where('status', 'paid')->sum('total_amount');
        $avgRating = Feedback::avg('rating') ?? 0;
        $waitersOnline = User::role('waiter')->where('restaurant_id', $restaurantId)->count(); // Simplified online check

        // Live Orders
        $pendingOrders = Order::with('items.menuItem')->where('status', 'pending')->latest()->get();
        $preparingOrders = Order::with('items.menuItem')->where('status', 'preparing')->latest()->get();
        $servedOrders = Order::with('items.menuItem')->where('status', 'served')->latest()->get();
        $paidOrders = Order::with('items.menuItem')->where('status', 'paid')->whereDate('created_at', $today)->latest()->take(10)->get();

        // Feedback
        $recentFeedback = Feedback::with('order')->latest()->take(5)->get();

        // Tips
        $waiterTips = Tip::with('waiter')
            ->whereDate('created_at', $today)
            ->selectRaw('waiter_id, SUM(amount) as total_amount')
            ->groupBy('waiter_id')
            ->orderByDesc('total_amount')
            ->get();

        return view('manager.dashboard', compact(
            'totalOrdersToday',
            'revenueToday',
            'avgRating',
            'waitersOnline',
            'pendingOrders',
            'preparingOrders',
            'servedOrders',
            'paidOrders',
            'recentFeedback',
            'waiterTips'
        ));
    }
    public function getStats()
    {
        $restaurantId = Auth::user()->restaurant_id;
        $today = Carbon::today();

        $stats = [
            'total_orders_today' => Order::whereDate('created_at', $today)->count(),
            'revenue_today' => Order::whereDate('created_at', $today)->where('status', 'paid')->sum('total_amount'),
            'avg_rating' => number_format(Feedback::avg('rating') ?? 0, 1),
            'waiters_online' => User::role('waiter')->where('restaurant_id', $restaurantId)->count(),
        ];

        return response()->json($stats);
    }
}
