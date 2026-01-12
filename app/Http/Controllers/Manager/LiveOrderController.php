<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use Carbon\Carbon;

class LiveOrderController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $restaurantId = auth()->user()->restaurant_id;

        $pendingOrders = Order::with('items.menuItem')
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $preparingOrders = Order::with('items.menuItem')
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'preparing')
            ->latest()
            ->get();

        $servedOrders = Order::with('items.menuItem')
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'served')
            ->latest()
            ->get();

        $paidOrders = Order::with('items.menuItem')
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'paid')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(20)
            ->get();

        $tables = \App\Models\Table::where('restaurant_id', $restaurantId)->get();
        $menuItems = \App\Models\MenuItem::where('restaurant_id', $restaurantId)->where('is_available', true)->get();

        return view('manager.orders.live', compact('pendingOrders', 'preparingOrders', 'servedOrders', 'paidOrders', 'tables', 'menuItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $itemData) {
            $menuItem = \App\Models\MenuItem::find($itemData['id']);
            $subtotal = $menuItem->price * $itemData['quantity'];
            $totalAmount += $subtotal;

            $orderItems[] = [
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'quantity' => $itemData['quantity'],
                'price' => $menuItem->price,
                'total' => $subtotal,
            ];
        }

        $order = Order::create([
            'restaurant_id' => auth()->user()->restaurant_id,
            'table_number' => $request->table_number,
            'customer_phone' => $request->customer_phone,
            'customer_name' => $request->customer_name,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->back()->with('success', 'Order created successfully');
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($request->has('status')) {
            $order->update(['status' => $request->status]);
        }

        if ($request->has('table_number')) {
            $order->update([
                'table_number' => $request->table_number,
                'customer_phone' => $request->customer_phone,
                'customer_name' => $request->customer_name,
            ]);
        }

        return redirect()->back()->with('success', 'Order updated successfully');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->back()->with('success', 'Order deleted successfully');
    }
}
