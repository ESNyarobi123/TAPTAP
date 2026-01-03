<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = \App\Models\Order::with(['restaurant', 'items.menuItem'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(string $id)
    {
        $order = \App\Models\Order::with(['restaurant', 'items.menuItem', 'payment', 'waiter'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(string $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, string $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }

    public function destroy(string $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
