<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class MenuController extends Controller
{
    public function categories(Restaurant $restaurant)
    {
        return response()->json($restaurant->categories()->orderBy('sort_order')->get());
    }

    public function index(Request $request, Restaurant $restaurant)
    {
        $query = $restaurant->menuItems()->where('is_available', true);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->get());
    }
}
