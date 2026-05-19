<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LiveOrderController extends Controller
{
    public function index(Request $request): View
    {
        $restaurantId = $request->integer('restaurant_id') ?: null;

        $baseQuery = Order::query()
            ->with(['restaurant', 'items.menuItem', 'waiter'])
            ->when($restaurantId, fn ($q) => $q->where('restaurant_id', $restaurantId));

        $pendingOrders = (clone $baseQuery)->where('status', 'pending')->latest()->limit(50)->get();
        $preparingOrders = (clone $baseQuery)->where('status', 'preparing')->latest()->limit(50)->get();
        $readyOrders = (clone $baseQuery)->where('status', 'ready')->latest()->limit(50)->get();
        $servedOrders = (clone $baseQuery)->where('status', 'served')->latest()->limit(50)->get();

        $restaurants = Restaurant::query()->orderBy('name')->get(['id', 'name']);

        $counts = [
            'pending' => Order::query()->when($restaurantId, fn ($q) => $q->where('restaurant_id', $restaurantId))->where('status', 'pending')->count(),
            'preparing' => Order::query()->when($restaurantId, fn ($q) => $q->where('restaurant_id', $restaurantId))->where('status', 'preparing')->count(),
            'ready' => Order::query()->when($restaurantId, fn ($q) => $q->where('restaurant_id', $restaurantId))->where('status', 'ready')->count(),
            'served' => Order::query()->when($restaurantId, fn ($q) => $q->where('restaurant_id', $restaurantId))->where('status', 'served')->count(),
        ];

        return view('admin.live-orders.index', compact(
            'pendingOrders',
            'preparingOrders',
            'readyOrders',
            'servedOrders',
            'restaurants',
            'restaurantId',
            'counts',
        ));
    }
}
