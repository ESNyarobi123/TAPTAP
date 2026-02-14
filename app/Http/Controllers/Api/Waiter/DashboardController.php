<?php

namespace App\Http\Controllers\Api\Waiter;

use App\Http\Controllers\Controller;
use App\Models\CustomerRequest;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\Tip;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Waiter Dashboard API - Returns stats and data for mobile app
     */
    public function index(): JsonResponse
    {
        $waiter = Auth::user();
        $today = Carbon::today();

        $tipsToday = Tip::where('waiter_id', $waiter->id)->whereDate('created_at', $today)->sum('amount');
        $tipsThisWeek = Tip::where('waiter_id', $waiter->id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');

        $myActiveOrders = Order::where('waiter_id', $waiter->id)->whereIn('status', ['pending', 'preparing', 'ready'])->count();
        $restaurantActiveOrders = Order::whereIn('status', ['pending', 'preparing', 'ready'])->count();
        $readyToServeOrders = Order::where('status', 'ready')->count();

        $unassignedOrders = Order::with('items.menuItem')
            ->whereNull('waiter_id')
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->get()
            ->map(fn ($order) => [
                'id' => $order->id,
                'table_number' => $order->table_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'created_at' => $order->created_at->toIso8601String(),
                'items' => $order->items->map(fn ($item) => [
                    'name' => $item->name ?? ($item->menuItem?->name ?? 'Custom Order'),
                    'quantity' => $item->quantity,
                ]),
            ]);

        $pendingRequests = CustomerRequest::where('status', 'pending')->latest()->get()->map(fn ($req) => [
            'id' => $req->id,
            'type' => $req->type,
            'table_number' => $req->table_number,
            'created_at' => $req->created_at->toIso8601String(),
        ]);

        $recentFeedback = Feedback::where(function ($query) use ($waiter) {
            $query->where('waiter_id', $waiter->id)
                ->orWhereHas('order', fn ($q) => $q->where('waiter_id', $waiter->id));
        })->latest()->take(5)->get()->map(fn ($f) => [
            'id' => $f->id,
            'rating' => $f->rating,
            'comment' => $f->comment,
            'created_at' => $f->created_at->toIso8601String(),
        ]);

        $myOrders = Order::with('items.menuItem')
            ->where('waiter_id', $waiter->id)
            ->whereDate('created_at', $today)
            ->latest()
            ->get()
            ->map(fn ($order) => [
                'id' => $order->id,
                'table_number' => $order->table_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'items_count' => $order->items->count(),
                'created_at' => $order->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'tips_today' => $tipsToday,
                    'tips_today_amount' => $tipsToday,
                    'tips_this_week' => $tipsThisWeek,
                    'tips_this_week_amount' => $tipsThisWeek,
                    'total_tips_received' => Tip::where('waiter_id', $waiter->id)->sum('amount'),
                    'my_active_orders' => $myActiveOrders,
                    'restaurant_active_orders' => $restaurantActiveOrders,
                    'ready_to_serve' => $readyToServeOrders,
                    'pending_requests' => $pendingRequests->count(),
                ],
                'unassigned_orders' => $unassignedOrders->values(),
                'pending_requests' => $pendingRequests->values(),
                'recent_feedback' => $recentFeedback->values(),
                'my_orders_today' => $myOrders->values(),
            ],
        ]);
    }

    /**
     * Quick stats only (for polling/refresh)
     */
    public function stats(): JsonResponse
    {
        $waiter = Auth::user();
        $today = Carbon::today();

        $tipsTodayAmount = Tip::where('waiter_id', $waiter->id)->whereDate('created_at', $today)->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'tips_today' => $tipsTodayAmount,
                'tips_today_amount' => $tipsTodayAmount,
                'total_tips_received' => Tip::where('waiter_id', $waiter->id)->sum('amount'),
                'my_active_orders' => Order::where('waiter_id', $waiter->id)->whereIn('status', ['pending', 'preparing', 'ready'])->count(),
                'ready_to_serve' => Order::where('status', 'ready')->count(),
                'pending_requests' => CustomerRequest::where('status', 'pending')->count(),
            ],
        ]);
    }

    /**
     * Claim an unassigned order
     */
    public function claimOrder(Order $order): JsonResponse
    {
        if ($order->waiter_id) {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been claimed by another waiter.',
            ], 422);
        }

        $waiterId = Auth::id();
        $order->update(['waiter_id' => $waiterId]);

        Tip::where('order_id', $order->id)
            ->whereNull('waiter_id')
            ->update(['waiter_id' => $waiterId]);

        return response()->json([
            'success' => true,
            'message' => 'Order #'.$order->id.' is now assigned to you!',
            'data' => [
                'order_id' => $order->id,
                'table_number' => $order->table_number,
            ],
        ]);
    }

    /**
     * Mark customer request (call waiter / request bill) as completed
     */
    public function completeRequest(CustomerRequest $customerRequest): JsonResponse
    {
        $customerRequest->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Request marked as attended!',
            'data' => ['request_id' => $customerRequest->id],
        ]);
    }

    /**
     * List waiter's orders (paginated)
     */
    public function orders(): JsonResponse
    {
        $waiter = Auth::user();
        $orders = Order::with('items.menuItem')
            ->where('waiter_id', $waiter->id)
            ->latest()
            ->paginate(15);

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'table_number' => $order->table_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'created_at' => $order->created_at->toIso8601String(),
                'items' => $order->items->map(fn ($item) => [
                    'name' => $item->name ?? ($item->menuItem?->name ?? 'Custom Order'),
                    'quantity' => $item->quantity,
                    'price' => $item->price ?? $item->menuItem?->price,
                ]),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * List waiter's tips (paginated)
     */
    public function tips(): JsonResponse
    {
        $waiter = Auth::user();
        $tips = Tip::where('waiter_id', $waiter->id)->latest()->paginate(15);
        $totalTips = Tip::where('waiter_id', $waiter->id)->sum('amount');

        $tips->getCollection()->transform(fn ($t) => [
            'id' => $t->id,
            'order_id' => $t->order_id,
            'amount' => (float) $t->amount,
            'amount_received' => (float) $t->amount,
            'created_at' => $t->created_at->toIso8601String(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'total_tips' => $totalTips,
                'total_amount_received' => $totalTips,
                'summary' => [
                    'total_tips' => $totalTips,
                    'today' => Tip::where('waiter_id', $waiter->id)->whereDate('created_at', Carbon::today())->sum('amount'),
                    'this_week' => Tip::where('waiter_id', $waiter->id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount'),
                ],
                'tips' => $tips,
            ],
        ]);
    }

    /**
     * List waiter's ratings/feedback (paginated)
     */
    public function ratings(): JsonResponse
    {
        $waiter = Auth::user();
        $feedbacks = Feedback::where(function ($query) use ($waiter) {
            $query->where('waiter_id', $waiter->id)
                ->orWhereHas('order', fn ($q) => $q->where('waiter_id', $waiter->id));
        })->with('order')->latest()->paginate(15);

        $feedbacks->getCollection()->transform(function ($f) {
            return [
                'id' => $f->id,
                'rating' => $f->rating,
                'comment' => $f->comment,
                'table_number' => $f->order?->table_number,
                'created_at' => $f->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $feedbacks,
        ]);
    }

    /**
     * List pending customer requests (call waiter / request bill)
     */
    public function pendingRequests(): JsonResponse
    {
        $requests = CustomerRequest::where('status', 'pending')->latest()->get()->map(fn ($req) => [
            'id' => $req->id,
            'type' => $req->type,
            'table_number' => $req->table_number,
            'waiter_id' => $req->waiter_id,
            'created_at' => $req->created_at->toIso8601String(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $requests->values(),
        ]);
    }
}
