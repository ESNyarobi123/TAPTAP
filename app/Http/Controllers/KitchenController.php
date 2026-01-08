<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class KitchenController extends Controller
{
    /**
     * Display the kitchen display system
     */
    public function display($token)
    {
        $restaurant = Restaurant::where('kitchen_token', $token)->firstOrFail();
        
        return view('kitchen.display', compact('restaurant'));
    }

    /**
     * Get orders for kitchen display (API endpoint for real-time updates)
     */
    public function getOrders($token)
    {
        $restaurant = Restaurant::where('kitchen_token', $token)->firstOrFail();
        
        $orders = Order::with(['items.menuItem', 'waiter'])
            ->where('restaurant_id', $restaurant->id)
            ->whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'confirmed' THEN 2 
                WHEN status = 'preparing' THEN 3 
                ELSE 4 
            END")
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($order) {
                $createdAt = $order->created_at;
                $now = now();
                $elapsedMinutes = $createdAt->diffInMinutes($now);
                
                // SLA: 15 min = green, 25 min = yellow, 30+ = red
                $slaStatus = 'green';
                if ($elapsedMinutes > 25) {
                    $slaStatus = 'red';
                } elseif ($elapsedMinutes > 15) {
                    $slaStatus = 'yellow';
                }
                
                return [
                    'id' => $order->id,
                    'table_number' => $order->table_number,
                    'status' => $order->status,
                    'is_vip' => $order->is_vip ?? false,
                    'waiter_name' => $order->waiter?->name ?? 'Unassigned',
                    'elapsed_minutes' => $elapsedMinutes,
                    'elapsed_time' => $this->formatElapsedTime($elapsedMinutes),
                    'sla_status' => $slaStatus,
                    'created_at' => $createdAt->format('H:i'),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->menuItem->name ?? 'Unknown Item',
                            'quantity' => $item->quantity,
                            'notes' => $item->notes ?? '',
                            'status' => $item->status ?? 'pending'
                        ];
                    })
                ];
            });
        
        return response()->json([
            'success' => true,
            'orders' => $orders,
            'stats' => [
                'pending' => $orders->where('status', 'pending')->count(),
                'preparing' => $orders->where('status', 'preparing')->count(),
                'total' => $orders->count(),
                'overdue' => $orders->where('sla_status', 'red')->count()
            ],
            'timestamp' => now()->format('H:i:s')
        ]);
    }

    /**
     * Update order status from kitchen
     */
    public function updateStatus(Request $request, $token)
    {
        $restaurant = Restaurant::where('kitchen_token', $token)->firstOrFail();
        
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:preparing,ready,completed'
        ]);
        
        $order = Order::where('id', $request->order_id)
            ->where('restaurant_id', $restaurant->id)
            ->firstOrFail();
        
        $order->update(['status' => $request->status]);
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated',
            'order_id' => $order->id,
            'new_status' => $request->status
        ]);
    }

    /**
     * Mark individual item as cooking/ready
     */
    public function updateItemStatus(Request $request, $token)
    {
        $restaurant = Restaurant::where('kitchen_token', $token)->firstOrFail();
        
        $request->validate([
            'item_id' => 'required|exists:order_items,id',
            'status' => 'required|in:pending,cooking,ready'
        ]);
        
        $item = \App\Models\OrderItem::where('id', $request->item_id)
            ->whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })
            ->firstOrFail();
        
        $item->update(['status' => $request->status]);
        
        // If all items are ready, mark order as ready
        $order = $item->order;
        $allReady = $order->items()->where('status', '!=', 'ready')->count() === 0;
        if ($allReady) {
            $order->update(['status' => 'ready']);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Item status updated',
            'item_id' => $item->id,
            'new_status' => $request->status,
            'order_ready' => $allReady
        ]);
    }

    /**
     * Generate new kitchen token (Manager only)
     */
    public function generateToken(Request $request)
    {
        $user = Auth::user();
        $restaurant = Restaurant::findOrFail($user->restaurant_id);
        
        $restaurant->update([
            'kitchen_token' => Str::random(32),
            'kitchen_token_generated_at' => now()
        ]);
        
        return back()->with('success', 'Kitchen display link generated successfully!');
    }

    /**
     * Revoke kitchen token (Manager only)
     */
    public function revokeToken(Request $request)
    {
        $user = Auth::user();
        $restaurant = Restaurant::findOrFail($user->restaurant_id);
        
        $restaurant->update([
            'kitchen_token' => null,
            'kitchen_token_generated_at' => null
        ]);
        
        return back()->with('success', 'Kitchen display link revoked!');
    }

    /**
     * Format elapsed time for display
     */
    private function formatElapsedTime($minutes)
    {
        if ($minutes < 1) {
            return 'Just now';
        } elseif ($minutes < 60) {
            return $minutes . 'm';
        } else {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            return $hours . 'h ' . $mins . 'm';
        }
    }
}
