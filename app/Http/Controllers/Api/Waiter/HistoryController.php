<?php

namespace App\Http\Controllers\Api\Waiter;

use App\Http\Controllers\Controller;
use App\Models\WaiterRestaurantAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Waiter's own work history (restaurant assignments – linked/unlinked).
     * GET /api/waiter/history
     */
    public function index(): JsonResponse
    {
        $assignments = WaiterRestaurantAssignment::query()
            ->where('user_id', Auth::id())
            ->with('restaurant:id,name,location,phone')
            ->orderByDesc('linked_at')
            ->limit(200)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'restaurant_id' => $a->restaurant_id,
                'restaurant_name' => $a->restaurant?->name,
                'restaurant_location' => $a->restaurant?->location,
                'restaurant_phone' => $a->restaurant?->phone,
                'employment_type' => $a->employment_type,
                'linked_until' => $a->linked_until?->format('Y-m-d'),
                'linked_at' => $a->linked_at?->toIso8601String(),
                'unlinked_at' => $a->unlinked_at?->toIso8601String(),
                'is_active' => $a->unlinked_at === null,
            ]);

        return response()->json([
            'success' => true,
            'data' => $assignments->values(),
        ]);
    }
}
