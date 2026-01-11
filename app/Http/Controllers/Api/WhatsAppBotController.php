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
use App\Models\Table;
use App\Models\Feedback;
use App\Models\Tip;
use App\Models\User;
use App\Models\CustomerRequest;
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
        $categories = Category::withoutGlobalScopes()->where('restaurant_id', $restaurantId)
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
        $items = MenuItem::withoutGlobalScopes()->where('category_id', $categoryId)
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
        $item = MenuItem::withoutGlobalScopes()->with(['category' => function($query) {
            $query->withoutGlobalScopes();
        }])->find($itemId);

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
        $categories = Category::withoutGlobalScopes()->with(['menuItems' => function($query) {
            $query->withoutGlobalScopes()->where('is_available', true);
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
                    $menuItem = MenuItem::withoutGlobalScopes()->find($itemData['menu_item_id']);
                    $subtotal = $menuItem->price * $itemData['quantity'];
                    $totalAmount += $subtotal;

                    $orderItems[] = [
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $itemData['quantity'],
                        'price' => $menuItem->price,
                        'total' => $subtotal,
                    ];
                }

                $order = Order::withoutGlobalScopes()->create([
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
     * Check Order & Payment Status (Polling)
     */
    public function getOrderStatus($orderId)
    {
        $order = Order::withoutGlobalScopes()->with(['restaurant', 'items.menuItem' => function($query) {
            $query->withoutGlobalScopes();
        }, 'payments'])->find($orderId);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $payment = $order->payments()->where('method', 'ussd')->latest()->first();
        
        // Polling logic for ZenoPay
        if ($payment && $payment->status === 'pending') {
            $restaurant = $order->restaurant;
            $apiKey = $restaurant->zenopay_api_key;

            if ($apiKey) {
                $zenoPay = new \App\Services\ZenoPayService();
                $result = $zenoPay->checkStatus($apiKey, $payment->transaction_reference);

                if (
                    (isset($result['payment_status']) && ($result['payment_status'] === 'COMPLETED' || $result['payment_status'] === 'SUCCESS')) || 
                    (isset($result['result']) && $result['result'] === 'SUCCESS')
                ) {
                    $payment->update(['status' => 'paid']);
                    $order->update(['status' => 'paid']);
                } elseif (isset($result['payment_status']) && $result['payment_status'] === 'FAILED') {
                    $payment->update(['status' => 'failed']);
                }
            }
        }

        return response()->json([
            'success' => true,
            'status' => $order->status,
            'payment_status' => $payment ? $payment->status : 'unpaid',
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

        $feedback = Feedback::withoutGlobalScopes()->create($request->all());

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

        try {
            // Get waiter_id from order if exists
            $order = Order::withoutGlobalScopes()->find($request->order_id);
            $waiterId = $order ? $order->waiter_id : null;

            $tip = Tip::withoutGlobalScopes()->create([
                'restaurant_id' => $request->restaurant_id,
                'order_id' => $request->order_id,
                'waiter_id' => $waiterId,
                'amount' => $request->amount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tip submitted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit tip: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Initiate USSD Payment
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required',
            'amount' => 'required|numeric',
            'network' => 'nullable|string'
        ]);

        $order = Order::withoutGlobalScopes()->with('restaurant')->find($request->order_id);
        $restaurant = $order->restaurant;

        if (!$restaurant || !$restaurant->zenopay_api_key) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant payment gateway not configured'
            ], 400);
        }

        // Prepare data for ZenoPay
        $transactionId = 'BOT-' . $order->id . '-' . time();
        
        // Map network names if necessary (e.g., tigopesa -> tigo)
        $network = $request->network;
        if ($network === 'tigopesa') {
            $network = 'tigo';
        } elseif ($network === 'mpesa') {
            $network = 'voda';
        } elseif ($network === 'halopesa') {
            $network = 'halo';
        }

        $zenoPay = new \App\Services\ZenoPayService();
        $result = $zenoPay->initiatePayment($restaurant->zenopay_api_key, [
            'order_id' => $transactionId,
            'buyer_email' => $order->customer_phone . '@taptap.com', // Placeholder email
            'buyer_name' => 'WhatsApp Customer',
            'buyer_phone' => $request->phone_number,
            'amount' => $request->amount,
            'network' => $network
        ]);

        if (isset($result['status']) && $result['status'] === 'success') {
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $request->amount,
                'method' => 'ussd',
                'status' => 'pending',
                'transaction_reference' => $transactionId,
            ]);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'message' => 'USSD Prompt sent to ' . $request->phone_number
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to initiate payment with ZenoPay',
            'debug' => $result // Optional: remove in production
        ], 400);
    }

    /**
     * Initiate Quick Payment (without order)
     * Allows payment of any amount with a description
     */
    public function initiateQuickPayment(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'phone_number' => 'required',
            'amount' => 'required|numeric|min:100',
            'description' => 'required|string|max:500',
            'network' => 'nullable|string'
        ]);

        $restaurant = Restaurant::find($request->restaurant_id);

        if (!$restaurant || !$restaurant->zenopay_api_key) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant payment gateway not configured'
            ], 400);
        }

        // Prepare data for ZenoPay
        $transactionId = 'QUICK-' . $restaurant->id . '-' . time();
        
        // Map network names if necessary (e.g., tigopesa -> tigo)
        $network = $request->network;
        if ($network === 'tigopesa') {
            $network = 'tigo';
        } elseif ($network === 'mpesa') {
            $network = 'voda';
        } elseif ($network === 'halopesa') {
            $network = 'halo';
        }

        $zenoPay = new \App\Services\ZenoPayService();
        $result = $zenoPay->initiatePayment($restaurant->zenopay_api_key, [
            'order_id' => $transactionId,
            'buyer_email' => $request->phone_number . '@taptap.com', // Placeholder email
            'buyer_name' => 'WhatsApp Customer',
            'buyer_phone' => $request->phone_number,
            'amount' => $request->amount,
            'network' => $network
        ]);

        if (isset($result['status']) && $result['status'] === 'success') {
            $payment = Payment::create([
                'restaurant_id' => $restaurant->id,
                'customer_phone' => $request->phone_number,
                'amount' => $request->amount,
                'method' => 'ussd',
                'payment_type' => 'quick',
                'status' => 'pending',
                'transaction_reference' => $transactionId,
                'description' => $request->description,
            ]);

            // Log Activity
            Activity::create([
                'description' => "Quick payment initiated: Tsh " . number_format($request->amount) . " from {$request->phone_number}",
                'type' => 'quick_payment',
                'properties' => [
                    'payment_id' => $payment->id, 
                    'source' => 'whatsapp',
                    'description' => $request->description
                ]
            ]);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'message' => 'USSD Prompt sent to ' . $request->phone_number,
                'description' => $request->description
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to initiate payment with ZenoPay',
            'debug' => $result
        ], 400);
    }

    /**
     * Check Quick Payment Status (Polling)
     */
    public function getQuickPaymentStatus($paymentId)
    {
        $payment = Payment::where('id', $paymentId)
            ->where('payment_type', 'quick')
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        // Polling logic for ZenoPay
        if ($payment->status === 'pending' && $payment->restaurant) {
            $restaurant = $payment->restaurant;
            $apiKey = $restaurant->zenopay_api_key;

            if ($apiKey) {
                $zenoPay = new \App\Services\ZenoPayService();
                $result = $zenoPay->checkStatus($apiKey, $payment->transaction_reference);

                if (
                    (isset($result['payment_status']) && ($result['payment_status'] === 'COMPLETED' || $result['payment_status'] === 'SUCCESS')) || 
                    (isset($result['result']) && $result['result'] === 'SUCCESS')
                ) {
                    $payment->update(['status' => 'paid']);
                } elseif (isset($result['payment_status']) && $result['payment_status'] === 'FAILED') {
                    $payment->update(['status' => 'failed']);
                }
            }
        }

        return response()->json([
            'success' => true,
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'description' => $payment->description,
            'created_at' => $payment->created_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get Tables for a Restaurant
     */
    public function getTables($restaurantId)
    {
        $tables = Table::withoutGlobalScopes()->where('restaurant_id', $restaurantId)
            ->where('is_active', true)
            ->get(['id', 'name', 'capacity']);

        return response()->json([
            'success' => true,
            'data' => $tables
        ]);
    }
    /**
     * Call Waiter from Bot
     */
    public function callWaiter(Request $request)
    {
        // Handle both 'type' and 'request_type' (from bot)
        $type = $request->input('type') ?? $request->input('request_type');
        
        // Map bot values to DB values
        if ($type === 'Call Waiter') $type = 'call_waiter';
        if ($type === 'Request Bill') $type = 'request_bill';

        $request->merge(['type' => $type]);

        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_number' => 'required',
            'type' => 'required|in:call_waiter,request_bill',
        ]);

        $customerRequest = CustomerRequest::withoutGlobalScopes()->create([
            'restaurant_id' => $request->restaurant_id,
            'table_number' => $request->table_number,
            'type' => $request->type,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->type === 'request_bill' ? 'Bill request sent' : 'Waiter called successfully'
        ]);
    }

    /**
     * Get Waiters for a Restaurant
     */
    public function getWaiters($restaurantId)
    {
        $waiters = User::role('waiter')
            ->where('restaurant_id', $restaurantId)
            ->get(['id', 'name']);
        return response()->json([
            'success' => true,
            'data' => $waiters
        ]);
    }

    /**
     * Get Active Order (Bill) for a Table
     */
    public function getActiveOrder(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_number' => 'required',
        ]);

        $order = Order::withoutGlobalScopes()
            ->where('restaurant_id', $request->restaurant_id)
            ->where('table_number', $request->table_number)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->with(['items.menuItem' => function($query) {
                $query->withoutGlobalScopes();
            }])
            ->latest()
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No active order found for this table'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $order->id,
                'total' => $order->total_amount,
                'status' => $order->status,
                'items' => $order->items->map(function($item) {
                    return [
                        'name' => $item->menuItem->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->total
                    ];
                })
            ]
        ]);
    }

    /**
     * Get Menu Image for a Restaurant
     */
    public function getMenuImage($restaurantId)
    {
        $restaurant = Restaurant::find($restaurantId);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found'
            ], 404);
        }

        if (!$restaurant->menu_image) {
            return response()->json([
                'success' => false,
                'message' => 'No menu image available for this restaurant'
            ], 404);
        }

        // Return the full URL to the menu image
        $imageUrl = asset('storage/' . $restaurant->menu_image);

        return response()->json([
            'success' => true,
            'data' => [
                'restaurant_id' => $restaurant->id,
                'restaurant_name' => $restaurant->name,
                'menu_image_url' => $imageUrl
            ]
        ]);
    }

    /**
     * Create Order from Text (Natural Language)
     */
    public function createOrderByText(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_number' => 'required',
            'customer_phone' => 'required',
            'order_text' => 'required|string',
        ]);

        $text = $request->order_text;
        $restaurantId = $request->restaurant_id;

        // Fetch all available items for this restaurant
        $menuItems = MenuItem::withoutGlobalScopes()
            ->where('restaurant_id', $restaurantId)
            ->where('is_available', true)
            ->get();

        // Sort by name length descending to match longest names first (e.g. "Chips Mayai" before "Chips")
        $sortedItems = $menuItems->sortByDesc(function($item) {
            return strlen($item->name);
        });

        $matchedItems = [];
        $totalAmount = 0;

        foreach ($sortedItems as $item) {
            // Escape special characters in item name for regex
            $escapedName = preg_quote($item->name, '/');
            
            // Regex to find item name, optionally preceded OR followed by a number (quantity)
            // Matches: "2 Chips", "Chips 2", "2x Chips", "Chips x2"
            $regex = '/(?:(\d+)\s*[xX]?\s*)?\b' . $escapedName . '\b(?:\s*[xX]?\s*(\d+))?/i';
            
            if (preg_match($regex, $text, $matches)) {
                $q1 = isset($matches[1]) && $matches[1] !== '' ? (int)$matches[1] : 0;
                $q2 = isset($matches[2]) && $matches[2] !== '' ? (int)$matches[2] : 0;
                $quantity = max($q1, $q2, 1);
                
                $matchedItems[] = [
                    'menu_item_id' => $item->id,
                    'quantity' => $quantity,
                    'price' => $item->price,
                    'name' => $item->name, // For response
                    'subtotal' => $item->price * $quantity
                ];
                
                $totalAmount += $item->price * $quantity;
                
                // Remove the matched part from text to prevent double matching
                // We replace with spaces to preserve word boundaries for other matches
                $text = str_replace($matches[0], str_repeat(' ', strlen($matches[0])), $text);
            }
        }

        if (empty($matchedItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, we could not identify any menu items in your message. Please try using the exact names from the menu.'
            ], 400);
        }

        // Create Order
        try {
            return DB::transaction(function () use ($request, $matchedItems, $totalAmount) {
                $order = Order::withoutGlobalScopes()->create([
                    'restaurant_id' => $request->restaurant_id,
                    'table_number' => $request->table_number,
                    'customer_phone' => $request->customer_phone,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                ]);

                foreach ($matchedItems as $item) {
                    $order->items()->create([
                        'menu_item_id' => $item['menu_item_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['subtotal'],
                    ]);
                }

                // Log Activity
                Activity::create([
                    'description' => "New WhatsApp text order #{$order->id} from {$request->customer_phone}: \"{$request->order_text}\"",
                    'type' => 'order_created',
                    'properties' => ['order_id' => $order->id, 'source' => 'whatsapp_text']
                ]);

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'total' => $totalAmount,
                    'items' => $matchedItems,
                    'message' => 'Order created successfully'
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
