<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Category;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $menuItems = MenuItem::with('category')->latest()->get();
        return view('manager.menu.index', compact('categories', 'menuItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'preparation_time' => 'nullable|integer|min:1',
        ]);

        $data = $request->all();
        $data['restaurant_id'] = Auth::user()->restaurant_id;
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu_items', 'public');
        }

        MenuItem::create($data);

        return back()->with('success', 'Menu item added successfully!');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'preparation_time' => 'nullable|integer|min:1',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $data['image'] = $request->file('image')->store('menu_items', 'public');
        }

        $menuItem->update($data);

        return back()->with('success', 'Menu item updated successfully!');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }
        $menuItem->delete();

        return back()->with('success', 'Menu item deleted successfully!');
    }
}
