<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\WaiterRestaurantAssignment;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $assignments = WaiterRestaurantAssignment::query()
            ->where('user_id', Auth::id())
            ->with('restaurant:id,name,location,phone')
            ->orderByDesc('linked_at')
            ->get();

        return view('waiter.history.index', compact('assignments'));
    }
}
