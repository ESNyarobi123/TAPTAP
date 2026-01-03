<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $restaurants = \App\Models\Restaurant::all();
        return view('admin.notifications.index', compact('restaurants'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:all,managers,waiters,specific_restaurant',
            'restaurant_id' => 'nullable|exists:restaurants,id',
        ]);

        // Logic to send push notification would go here
        // For now, we'll just simulate it
        
        return back()->with('success', 'Push notification broadcasted successfully.');
    }
}
