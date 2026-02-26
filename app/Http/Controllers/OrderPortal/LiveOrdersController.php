<?php

namespace App\Http\Controllers\OrderPortal;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\Table;
use App\Services\SelcomService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LiveOrdersController extends Controller
{
    private function restaurantId(): int
    {
        return (int) session('order_portal_restaurant_id');
    }

    private function waiterId(): int
    {
        return (int) session('order_portal_user_id');
    }

    private function restaurant(): Restaurant
    {
        return Restaurant::findOrFail($this->restaurantId());
    }

    /**
     * Orders for this restaurant that belong to this waiter only (waiter_id = logged-in waiter).
     */
    private function orderQuery()
    {
        return Order::withoutGlobalScopes()
            ->where('restaurant_id', $this->restaurantId())
            ->where('waiter_id', $this->waiterId());
    }

    public function index()
    {
        $today = Carbon::today();
        $restaurantId = $this->restaurantId();

        $pendingOrders = $this->orderQuery()->with('items.menuItem')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $preparingOrders = $this->orderQuery()->with('items.menuItem')
            ->where('status', 'preparing')
            ->latest()
            ->get();

        $servedOrders = $this->orderQuery()->with('items.menuItem')
            ->where('status', 'served')
            ->latest()
            ->get();

        $paidOrders = $this->orderQuery()->with('items.menuItem')
            ->where('status', 'paid')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(20)
            ->get();

        $tables = Table::withoutGlobalScopes()->where('restaurant_id', $restaurantId)->get();
        $menuItems = MenuItem::withoutGlobalScopes()
            ->where('restaurant_id', $restaurantId)
            ->where('is_available', true)
            ->get();

        $restaurant = $this->restaurant();

        return view('order-portal.orders', compact(
            'pendingOrders', 'preparingOrders', 'servedOrders', 'paidOrders',
            'tables', 'menuItems', 'restaurant'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'table_number' => 'required|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $restaurantId = $this->restaurantId();
        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $itemData) {
            $menuItem = MenuItem::withoutGlobalScopes()->findOrFail($itemData['id']);
            if ($menuItem->restaurant_id != $restaurantId) {
                abort(403);
            }
            $subtotal = $menuItem->price * (int) $itemData['quantity'];
            $totalAmount += $subtotal;
            $orderItems[] = [
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'quantity' => (int) $itemData['quantity'],
                'price' => $menuItem->price,
                'total' => $subtotal,
            ];
        }

        $order = Order::withoutGlobalScopes()->create([
            'restaurant_id' => $restaurantId,
            'waiter_id' => $this->waiterId(),
            'table_number' => $request->table_number,
            'customer_phone' => $request->customer_phone ?? '',
            'customer_name' => $request->customer_name ?? '',
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->back()->with('success', 'Order created successfully.');
    }

    public function update(Request $request, int $order): RedirectResponse
    {
        $order = $this->orderQuery()->findOrFail($order);

        if ($request->has('status')) {
            $request->validate(['status' => 'in:pending,preparing,served,paid']);
            $order->update(['status' => $request->status]);
        }

        if ($request->has('table_number')) {
            $order->update([
                'table_number' => $request->table_number,
                'customer_phone' => $request->customer_phone ?? '',
                'customer_name' => $request->customer_name ?? '',
            ]);
        }

        if ($request->has('items') && is_array($request->items)) {
            $request->validate([
                'items' => 'array|min:0',
                'items.*.id' => 'required|exists:menu_items,id',
                'items.*.quantity' => 'required|integer|min:0',
            ]);
            $restaurantId = $this->restaurantId();
            $totalAmount = 0;
            $order->items()->delete();
            foreach ($request->items as $itemData) {
                $qty = (int) ($itemData['quantity'] ?? 0);
                if ($qty < 1) {
                    continue;
                }
                $menuItem = MenuItem::withoutGlobalScopes()->findOrFail($itemData['id']);
                if ($menuItem->restaurant_id != $restaurantId) {
                    continue;
                }
                $subtotal = $menuItem->price * $qty;
                $totalAmount += $subtotal;
                $order->items()->create([
                    'menu_item_id' => $menuItem->id,
                    'name' => $menuItem->name,
                    'quantity' => $qty,
                    'price' => $menuItem->price,
                    'total' => $subtotal,
                ]);
            }
            $order->update(['total_amount' => $totalAmount]);
        }

        return redirect()->back()->with('success', 'Order updated successfully.');
    }

    public function destroy(int $order): RedirectResponse
    {
        $order = $this->orderQuery()->findOrFail($order);
        $order->delete();

        return redirect()->back()->with('success', 'Order deleted.');
    }

    public function paymentInitiate(Request $request, SelcomService $selcom): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone' => 'required|string',
            'name' => 'nullable|string',
        ]);

        $order = $this->orderQuery()->findOrFail($request->order_id);
        $restaurant = $this->restaurant();

        if (! $restaurant->hasSelcomConfigured()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Selcom haijawekwa. Wasiliana na manager.',
            ], 400);
        }

        $transactionId = 'ORD-'.$order->id.'-'.time();

        $result = $selcom->initiatePayment($restaurant->getSelcomCredentials(), [
            'order_id' => $transactionId,
            'email' => 'customer@taptap.co.tz',
            'name' => $request->name ?? 'Customer',
            'phone' => $request->phone,
            'amount' => $order->total_amount,
            'description' => 'Order #'.$order->id,
        ]);

        if (isset($result['status']) && $result['status'] === 'success') {
            Payment::create([
                'order_id' => $order->id,
                'restaurant_id' => $restaurant->id,
                'customer_phone' => $request->phone,
                'amount' => $order->total_amount,
                'method' => 'ussd',
                'status' => 'pending',
                'transaction_reference' => $transactionId,
            ]);
            $order->update(['payment_reference' => $transactionId]);

            return response()->json([
                'status' => 'success',
                'message' => 'USSD Push sent to '.$request->phone,
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to initiate payment',
        ], 400);
    }

    public function paymentStatus(int $order, SelcomService $selcom): JsonResponse
    {
        $order = $this->orderQuery()->findOrFail($order);
        $restaurant = $this->restaurant();

        $payment = $order->payments()
            ->where('method', 'ussd')
            ->orderByDesc('created_at')
            ->first();

        if (! $payment || ! $payment->transaction_reference) {
            return response()->json(['status' => 'error', 'message' => 'No active payment found'], 400);
        }

        if ($payment->status === 'paid') {
            return response()->json(['status' => 'paid', 'message' => 'Payment already completed!']);
        }

        $result = $selcom->checkOrderStatus($restaurant->getSelcomCredentials(), $payment->transaction_reference);
        $paymentStatus = $selcom->parsePaymentStatus($result);

        if ($paymentStatus === 'paid') {
            $payment->update(['status' => 'paid']);
            $order->update(['status' => 'paid']);

            return response()->json(['status' => 'paid', 'message' => 'Payment completed successfully!']);
        }
        if ($paymentStatus === 'failed') {
            $payment->update(['status' => 'failed']);

            return response()->json(['status' => 'failed', 'message' => 'Payment failed or cancelled']);
        }

        return response()->json(['status' => 'pending', 'message' => 'Waiting for payment...']);
    }
}
