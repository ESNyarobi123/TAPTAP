<?php

namespace App\Http\Controllers\Api\Waiter;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    /**
     * Get restaurant menu (categories + items)
     */
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('sort_order')->get();
        $menuItems = MenuItem::with('category')->latest()->get();

        $categoriesData = $categories->map(function ($cat) use ($menuItems) {
            $items = $menuItems->where('category_id', $cat->id)->values()->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'image' => $item->image ? asset('storage/'.$item->image) : null,
                'is_available' => $item->is_available,
                'preparation_time' => $item->preparation_time ?? 15,
            ]);

            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'items' => $items,
            ];
        });

        $allItems = $menuItems->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'price' => $item->price,
            'category_id' => $item->category_id,
            'category_name' => $item->category?->name,
            'image' => $item->image ? asset('storage/'.$item->image) : null,
            'is_available' => $item->is_available,
            'preparation_time' => $item->preparation_time ?? 15,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categoriesData,
                'items' => $allItems->values(),
            ],
        ]);
    }
}
