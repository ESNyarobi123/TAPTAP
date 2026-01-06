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
            'pendingRequests',
            'recentFeedback',
            'myOrders'
        ));
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
}
