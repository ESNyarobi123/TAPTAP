<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tip;
use App\Models\Order;
use App\Models\CustomerRequest;
use App\Models\Feedback;
use Carbon\Carbon;
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
        $recentFeedback = Feedback::whereHas('order', function($query) use ($waiter) {
            $query->where('waiter_id', $waiter->id);
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

        return back()->with('success', 'Order #' . $order->id . ' is now assigned to you!');
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
        $feedbacks = Feedback::whereHas('order', function($query) use ($waiter) {
            $query->where('waiter_id', $waiter->id);
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
}
