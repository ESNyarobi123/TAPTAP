<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * List all menu items.
     */
    public function index()
    {
        $menuItems = MenuItem::with('category')->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $menuItems
        ]);
    }

    /**
     * Create a new menu item.
     */
    public function store(Request $request)
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
        $data['restaurant_id'] = Auth::user()->restaurant_id;
        // Handle boolean conversion explicitly for API JSON input or form-data
        $data['is_available'] = filter_var($request->input('is_available', true), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu_items', 'public');
        }

        $menuItem = MenuItem::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu item created successfully',
            'data' => $menuItem
        ], 201);
    }

    /**
     * Show a specific menu item.
     */
    public function show(MenuItem $menuItem)
    {
        return response()->json([
            'success' => true,
            'data' => $menuItem->load('category')
        ]);
    }

    /**
     * Update a menu item.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'price' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'preparation_time' => 'nullable|integer|min:1',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();
        
        if ($request->has('is_available')) {
             $data['is_available'] = filter_var($request->input('is_available'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $data['image'] = $request->file('image')->store('menu_items', 'public');
        }

        $menuItem->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated successfully',
            'data' => $menuItem
        ]);
    }

    /**
     * Delete a menu item.
     */
    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }
        $menuItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully'
        ]);
    }
}
