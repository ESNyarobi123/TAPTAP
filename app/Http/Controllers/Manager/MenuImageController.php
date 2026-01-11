<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Restaurant;

class MenuImageController extends Controller
{
    /**
     * Show the menu image management page.
     */
    public function index()
    {
        $restaurant = Restaurant::find(Auth::user()->restaurant_id);
        return view('manager.menu-image.index', compact('restaurant'));
    }

    /**
     * Upload/Update the menu image.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
        ]);

        $restaurant = Restaurant::find(Auth::user()->restaurant_id);

        if (!$restaurant) {
            return back()->with('error', 'Restaurant not found.');
        }

        // Delete old image if exists
        if ($restaurant->menu_image) {
            Storage::disk('public')->delete($restaurant->menu_image);
        }

        // Store new image
        $path = $request->file('menu_image')->store('menu_images', 'public');
        $restaurant->update(['menu_image' => $path]);

        return back()->with('success', 'Menu image uploaded successfully!');
    }

    /**
     * Delete the menu image.
     */
    public function destroy()
    {
        $restaurant = Restaurant::find(Auth::user()->restaurant_id);

        if (!$restaurant) {
            return back()->with('error', 'Restaurant not found.');
        }

        if ($restaurant->menu_image) {
            Storage::disk('public')->delete($restaurant->menu_image);
            $restaurant->update(['menu_image' => null]);
        }

        return back()->with('success', 'Menu image deleted successfully!');
    }
}
