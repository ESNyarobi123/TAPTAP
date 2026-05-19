<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $restaurantId = Auth::user()->restaurant_id;
        $today = Carbon::today();

        $stats = $this->buildStats($restaurantId, $today);

        $pendingOrders = Order::with(['items.menuItem'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $preparingOrders = Order::with(['items.menuItem'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'preparing')
            ->latest()
            ->get();

        $servedOrders = Order::with(['items.menuItem'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'served')
            ->latest()
            ->get();

        $paidOrders = Order::with(['items.menuItem'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'paid')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(10)
            ->get();

        $recentFeedback = Feedback::with(['order', 'waiter'])
            ->where('restaurant_id', $restaurantId)
            ->latest()
            ->take(5)
            ->get();

        return view('manager.dashboard', [
            'totalOrdersToday' => $stats['total_orders_today'],
            'revenueToday' => $stats['revenue_today'],
            'avgRating' => $stats['avg_rating'],
            'waitersOnline' => $stats['waiters_online'],
            'pendingOrders' => $pendingOrders,
            'preparingOrders' => $preparingOrders,
            'servedOrders' => $servedOrders,
            'paidOrders' => $paidOrders,
            'recentFeedback' => $recentFeedback,
        ]);
    }

    public function getStats(): JsonResponse
    {
        $restaurantId = Auth::user()->restaurant_id;
        $stats = $this->buildStats($restaurantId, Carbon::today());

        return response()->json([
            'total_orders_today' => $stats['total_orders_today'],
            'revenue_today' => $stats['revenue_today'],
            'avg_rating' => number_format($stats['avg_rating'], 1),
            'waiters_online' => $stats['waiters_online'],
        ]);
    }

    /**
     * @return array{total_orders_today: int, revenue_today: float, avg_rating: float, waiters_online: int}
     */
    private function buildStats(int $restaurantId, Carbon $today): array
    {
        $revenueToday = (float) Payment::query()
            ->where(function ($query) use ($restaurantId) {
                $query->where('restaurant_id', $restaurantId)
                    ->orWhereHas('order', fn ($orderQuery) => $orderQuery
                        ->where('restaurant_id', $restaurantId));
            })
            ->whereIn('status', ['paid', 'completed'])
            ->whereDate('created_at', $today)
            ->sum('amount');

        return [
            'total_orders_today' => Order::query()
                ->where('restaurant_id', $restaurantId)
                ->whereDate('created_at', $today)
                ->count(),
            'revenue_today' => $revenueToday,
            'avg_rating' => (float) (Feedback::query()
                ->where('restaurant_id', $restaurantId)
                ->avg('rating') ?? 0),
            'waiters_online' => User::role('waiter')
                ->where('restaurant_id', $restaurantId)
                ->where('is_online', true)
                ->count(),
        ];
    }
}
