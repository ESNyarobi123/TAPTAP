<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class WhatsAppBotController extends Controller
{
    /**
     * Search for restaurants (Optimized for WhatsApp Buttons - Max 3 results)
     */
    public function searchRestaurant(Request $request)
    {
        $query = $request->input('query');
        
        $restaurants = Restaurant::where('name', 'like', "%{$query}%")
            ->where('is_active', true)
            ->limit(3)
            ->get(['id', 'name', 'location']);

        return response()->json([
            'success' => true,
            'count' => $restaurants->count(),
            'data' => $restaurants
        ]);
    }

    /**
     * Verify Restaurant and Table (For QR Scan Entry)
     */
    public function verifyRestaurant(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $restaurant = Restaurant::find($request->restaurant_id);

        if (!$restaurant || !$restaurant->is_active) {
            return response()->json(['success' => false, 'message' => 'Restaurant not found or inactive'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'location' => $restaurant->location,
                'table_number' => $request->input('table_number')
            ]
        ]);
    }

    /**
     * Get Categories for a Restaurant
     */
    public function getCategories($restaurantId)
    {
        $categories = Category::where('restaurant_id', $restaurantId)
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get Menu Items for a Category
     */
    public function getCategoryItems($categoryId)
    {
        $items = MenuItem::where('category_id', $categoryId)
            ->where('is_available', true)
            ->get(['id', 'name', 'price', 'description', 'image']);

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Get Single Item Details
     */
    public function getItemDetails($itemId)
    {
        $item = MenuItem::with('category')->find($itemId);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    /**
     * Get Full Menu (Categories + Items) for a Restaurant
     */
    public function getFullMenu($restaurantId)
    {
        $categories = Category::with(['menuItems' => function($query) {
            $query->where('is_available', true);
        }])->where('restaurant_id', $restaurantId)->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Create Order from Bot
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_number' => 'required',
            'customer_phone' => 'required',
            'items' => 'required|array',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $orderItems = [];

                foreach ($request->items as $itemData) {
                    $menuItem = MenuItem::find($itemData['menu_item_id']);
                    $subtotal = $menuItem->price * $itemData['quantity'];
                    $totalAmount += $subtotal;

                    $orderItems[] = [
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $itemData['quantity'],
                        'price' => $menuItem->price,
                    ];
                }

                $order = Order::create([
                    'restaurant_id' => $request->restaurant_id,
                    'table_number' => $request->table_number,
                    'customer_phone' => $request->customer_phone,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                ]);

                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }

                // Log Activity
                Activity::create([
                    'description' => "New WhatsApp order #{$order->id} from {$request->customer_phone}",
                    'type' => 'order_created',
                    'properties' => ['order_id' => $order->id, 'source' => 'whatsapp']
                ]);

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'total' => $totalAmount,
                    'message' => 'Order created successfully'
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check Order Status
     */
    public function getOrderStatus($orderId)
    {
        $order = Order::with('items.menuItem')->find($orderId);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $order->status,
            'total' => $order->total_amount,
            'items' => $order->items
        ]);
    }

    /**
     * Submit Feedback from Bot
     */
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $feedback = Feedback::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully'
        ]);
    }

    /**
     * Submit Tip from Bot
     */
    public function submitTip(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Get waiter_id from order if exists
        $order = Order::find($request->order_id);
        $waiterId = $order->waiter_id;

        $tip = Tip::create([
            'restaurant_id' => $request->restaurant_id,
            'order_id' => $request->order_id,
            'waiter_id' => $waiterId,
            'amount' => $request->amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tip submitted successfully'
        ]);
    }

    /**
     * Initiate USSD Payment
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required',
            'amount' => 'required|numeric'
        ]);

        // Logic to trigger USSD via Gateway (ZenoPay etc)
        // For now, we simulate success
        
        $payment = Payment::create([
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'method' => 'ussd',
            'status' => 'pending',
            'transaction_reference' => 'BOT-' . strtoupper(uniqid()),
        ]);

        return response()->json([
            'success' => true,
            'payment_id' => $payment->id,
            'message' => 'USSD Prompt sent to ' . $request->phone_number
        ]);
    }
}
