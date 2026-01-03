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

        $pendingOrders = Order::with('items.menuItem')->where('status', 'pending')->latest()->get();
        $preparingOrders = Order::with('items.menuItem')->where('status', 'preparing')->latest()->get();
        $servedOrders = Order::with('items.menuItem')->where('status', 'served')->latest()->get();
        $paidOrders = Order::with('items.menuItem')->where('status', 'paid')->whereDate('created_at', $today)->latest()->take(20)->get();

        return view('manager.orders.live', compact('pendingOrders', 'preparingOrders', 'servedOrders', 'paidOrders'));
    }
}
