<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_restaurants' => \App\Models\Restaurant::count(),
            'active_orders' => \App\Models\Order::whereIn('status', ['pending', 'preparing', 'ready'])->count(),
            'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            'pending_withdrawals' => \App\Models\Withdrawal::where('status', 'pending')->count(),
        ];

        $recent_restaurants = \App\Models\Restaurant::latest()->take(5)->get();
        $recent_activities = \App\Models\Activity::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_restaurants', 'recent_activities'));
    }
    public function getStats()
    {
        $stats = [
            'total_restaurants' => \App\Models\Restaurant::count(),
            'active_orders' => \App\Models\Order::whereIn('status', ['pending', 'preparing', 'ready'])->count(),
            'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            'pending_withdrawals' => \App\Models\Withdrawal::where('status', 'pending')->count(),
        ];

        return response()->json($stats);
    }
}
