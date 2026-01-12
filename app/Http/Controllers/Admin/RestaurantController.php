<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurants = \App\Models\Restaurant::withCount(['users' => function($query) {
            $query->role('manager');
        }])->withCount(['users as waiters_count' => function($query) {
            $query->role('waiter');
        }])->latest()->paginate(10);

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function show(string $id)
    {
        $restaurant = \App\Models\Restaurant::with(['users' => function($query) {
            $query->role(['manager', 'waiter']);
        }])->findOrFail($id);

        $managers = $restaurant->users->filter(fn($u) => $u->hasRole('manager'));
        $waiters = $restaurant->users->filter(fn($u) => $u->hasRole('waiter'));

        return view('admin.restaurants.show', compact('restaurant', 'managers', 'waiters'));
    }

    public function edit(string $id)
    {
        $restaurant = \App\Models\Restaurant::findOrFail($id);
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, string $id)
    {
        $restaurant = \App\Models\Restaurant::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'selcom_vendor_id' => 'nullable|string|max:255',
            'selcom_api_key' => 'nullable|string|max:255',
            'selcom_api_secret' => 'nullable|string|max:255',
            'selcom_is_live' => 'nullable|boolean',
        ]);

        // Handle checkbox (not sent when unchecked)
        $validated['selcom_is_live'] = $request->has('selcom_is_live');

        $restaurant->update($validated);

        return redirect()->route('admin.restaurants.show', $restaurant)->with('success', 'Restaurant updated successfully.');
    }

    public function toggleStatus(string $id)
    {
        $restaurant = \App\Models\Restaurant::findOrFail($id);
        $restaurant->is_active = !$restaurant->is_active;
        $restaurant->save();

        $status = $restaurant->is_active ? 'activated' : 'blocked';
        return back()->with('success', "Restaurant has been {$status}.");
    }

    public function destroy(string $id)
    {
        $restaurant = \App\Models\Restaurant::findOrFail($id);
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted successfully.');
    }
}
