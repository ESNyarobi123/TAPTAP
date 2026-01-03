<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $restaurants = Restaurant::where('name', 'like', "%{$query}%")
            ->where('is_active', true)
            ->get();
        return response()->json($restaurants);
    }

    public function show(Restaurant $restaurant)
    {
        return response()->json($restaurant);
    }
}
