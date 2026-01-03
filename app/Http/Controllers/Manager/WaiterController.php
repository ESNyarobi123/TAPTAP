<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

class WaiterController extends Controller
{
    public function index()
    {
        $waiters = User::role('waiter')
            ->where('restaurant_id', Auth::user()->restaurant_id)
            ->withCount('orders')
            ->get();
            
        return view('manager.waiters.index', compact('waiters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $waiter = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'restaurant_id' => Auth::user()->restaurant_id,
        ]);

        $waiter->assignRole('waiter');

        return back()->with('success', 'Waiter added successfully!');
    }

    public function destroy(User $waiter)
    {
        // Ensure we are only deleting waiters from the current restaurant
        if ($waiter->restaurant_id !== Auth::user()->restaurant_id || !$waiter->hasRole('waiter')) {
            return back()->with('error', 'Unauthorized action.');
        }

        $waiter->delete();
        return back()->with('success', 'Waiter removed successfully!');
    }
}
