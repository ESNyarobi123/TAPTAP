<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\CustomerRequest;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\Table;
use App\Models\Tip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $waiter = Auth::user();
        $today = Carbon::today();

        // Tips
        $tipsToday = Tip::where('waiter_id', $waiter->id)->whereDate('created_at', $today)->sum('amount');
        $tipsThisWeek = Tip::where('waiter_id', $waiter->id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');

        // Orders Stats
        // 1. My Active Orders (Assigned to me)
        $myActiveOrders = Order::where('waiter_id', $waiter->id)->whereIn('status', ['pending', 'preparing', 'ready'])->count();

        // 2. All Active Restaurant Orders (To show workload)
        $restaurantActiveOrders = Order::whereIn('status', ['pending', 'preparing', 'ready'])->count();

        // 3. Orders Ready to Serve (High priority)
        $readyToServeOrders = Order::where('status', 'ready')->count();

        // 4. Unassigned Orders (Orders that need a waiter)
        $unassignedOrders = Order::with('items.menuItem')
            ->whereNull('waiter_id')
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->get();

        // Customer Requests (All pending requests for the restaurant)
        $pendingRequests = CustomerRequest::where('status', 'pending')->latest()->get();

        // Recent Feedback
        $recentFeedback = Feedback::where(function ($query) use ($waiter) {
            $query->where('waiter_id', $waiter->id)
                ->orWhereHas('order', function ($q) use ($waiter) {
                    $q->where('waiter_id', $waiter->id);
                });
        })->latest()->take(5)->get();

        // My Orders Today (History)
        $myOrders = Order::with('items.menuItem')
            ->where('waiter_id', $waiter->id)
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        return view('waiter.dashboard', compact(
            'tipsToday',
            'tipsThisWeek',
            'myActiveOrders',
            'restaurantActiveOrders',
            'readyToServeOrders',
            'unassignedOrders',
            'pendingRequests',
            'recentFeedback',
            'myOrders'
        ));
    }

    public function claimOrder($id)
    {
        $order = Order::findOrFail($id);

        if ($order->waiter_id) {
            return back()->with('error', 'This order has already been claimed by another waiter.');
        }

        $waiterId = Auth::id();
        $order->update(['waiter_id' => $waiterId]);

        // Also assign any existing tips for this order to this waiter
        Tip::where('order_id', $order->id)
            ->whereNull('waiter_id')
            ->update(['waiter_id' => $waiterId]);

        return back()->with('success', 'Order #'.$order->id.' is now assigned to you!');
    }

    public function completeRequest($id)
    {
        $request = CustomerRequest::findOrFail($id);
        $request->update(['status' => 'completed']);

        return back()->with('success', 'Request marked as attended!');
    }

    public function orders()
    {
        $waiter = Auth::user();
        $orders = Order::with('items.menuItem')
            ->where('waiter_id', $waiter->id)
            ->latest()
            ->paginate(15);

        return view('waiter.orders.index', compact('orders'));
    }

    public function tips()
    {
        $waiter = Auth::user();
        $tips = Tip::where('waiter_id', $waiter->id)->latest()->paginate(15);
        $totalTips = Tip::where('waiter_id', $waiter->id)->sum('amount');

        return view('waiter.tips.index', compact('tips', 'totalTips'));
    }

    public function ratings()
    {
        $waiter = Auth::user();
        $feedbacks = Feedback::where(function ($query) use ($waiter) {
            $query->where('waiter_id', $waiter->id)
                ->orWhereHas('order', function ($q) use ($waiter) {
                    $q->where('waiter_id', $waiter->id);
                });
        })->latest()->paginate(15);

        return view('waiter.ratings.index', compact('feedbacks'));
    }

    public function getStats()
    {
        $waiter = Auth::user();
        $today = Carbon::today();

        $stats = [
            'tips_today' => Tip::where('waiter_id', $waiter->id)->whereDate('created_at', $today)->sum('amount'),
            'my_active_orders' => Order::where('waiter_id', $waiter->id)->whereIn('status', ['pending', 'preparing', 'ready'])->count(),
            'ready_to_serve' => Order::where('status', 'ready')->count(),
            'pending_requests' => CustomerRequest::where('status', 'pending')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Hand over tables before departure: show my tables and colleagues.
     */
    public function handover()
    {
        $waiter = Auth::user();
        $myTables = Table::where('waiter_id', $waiter->id)->orderBy('name')->get();
        $colleagues = User::where('restaurant_id', $waiter->restaurant_id)
            ->where('id', '!=', $waiter->id)
            ->whereHas('roles', fn ($q) => $q->where('name', 'waiter'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('waiter.handover', compact('myTables', 'colleagues'));
    }

    /**
     * Process hand over of tables (before departure).
     */
    public function handoverSubmit(Request $request)
    {
        $waiter = Auth::user();
        $request->validate([
            'table_ids' => 'required|array',
            'table_ids.*' => 'integer|exists:tables,id',
            'hand_over_to_waiter_id' => 'nullable|integer|exists:users,id',
        ]);

        $tableIds = $request->input('table_ids');
        $targetWaiterId = $request->input('hand_over_to_waiter_id');

        $tables = Table::whereIn('id', $tableIds)->where('waiter_id', $waiter->id)->get();
        if ($tables->count() !== count($tableIds)) {
            return back()->with('error', 'Some tables are not assigned to you.');
        }

        if ($targetWaiterId !== null) {
            $target = User::where('id', $targetWaiterId)
                ->where('restaurant_id', $waiter->restaurant_id)
                ->whereHas('roles', fn ($q) => $q->where('name', 'waiter'))
                ->first();
            if (! $target) {
                return back()->with('error', 'Invalid hand over target.');
            }
        }

        Table::whereIn('id', $tableIds)->update(['waiter_id' => $targetWaiterId]);

        $message = $targetWaiterId
            ? 'Tables handed over successfully.'
            : 'Tables unassigned successfully.';

        return back()->with('success', $message);
    }
}
