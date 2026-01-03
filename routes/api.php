<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RestaurantController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\FeedbackController;
use App\Http\Controllers\Api\V1\TipController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Restaurants
    Route::get('/restaurants/search', [RestaurantController::class, 'search']);
    Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);

    // Menu
    Route::get('/restaurants/{restaurant}/categories', [MenuController::class, 'categories']);
    Route::get('/restaurants/{restaurant}/menu', [MenuController::class, 'index']);

    // Orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/orders/{order}/status', [OrderController::class, 'status']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    // Payments
    Route::post('/payments/ussd-request', [PaymentController::class, 'ussdRequest']);
    Route::post('/payments/cash', [PaymentController::class, 'cashPayment']);
    Route::get('/payments/{order}/status', [PaymentController::class, 'status']);
    
    // Feedback & Tips
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::post('/tips', [TipController::class, 'store']);
});

// Manager API Routes
Route::prefix('v1/manager')->middleware(['auth:sanctum', 'role:manager'])->group(function () {
    // Categories
    Route::apiResource('categories', \App\Http\Controllers\Api\Manager\CategoryController::class);
    
    // Menu
    Route::apiResource('menu', \App\Http\Controllers\Api\Manager\MenuController::class);
    
    // Tables
    Route::apiResource('tables', \App\Http\Controllers\Api\Manager\TableController::class);
});

// WhatsApp Bot Routes
Route::prefix('bot')->group(function () {
    Route::get('/verify-restaurant', [App\Http\Controllers\Api\WhatsAppBotController::class, 'verifyRestaurant']);
    Route::get('/search-restaurant', [App\Http\Controllers\Api\WhatsAppBotController::class, 'searchRestaurant']);
    Route::get('/restaurant/{restaurantId}/full-menu', [App\Http\Controllers\Api\WhatsAppBotController::class, 'getFullMenu']);
    Route::get('/restaurant/{restaurantId}/categories', [App\Http\Controllers\Api\WhatsAppBotController::class, 'getCategories']);
    Route::get('/category/{categoryId}/items', [App\Http\Controllers\Api\WhatsAppBotController::class, 'getCategoryItems']);
    Route::get('/item/{itemId}', [App\Http\Controllers\Api\WhatsAppBotController::class, 'getItemDetails']);
    Route::post('/order', [App\Http\Controllers\Api\WhatsAppBotController::class, 'createOrder']);
    Route::get('/order/{orderId}/status', [App\Http\Controllers\Api\WhatsAppBotController::class, 'getOrderStatus']);
    Route::post('/payment/ussd', [App\Http\Controllers\Api\WhatsAppBotController::class, 'initiatePayment']);
    Route::post('/feedback', [App\Http\Controllers\Api\WhatsAppBotController::class, 'submitFeedback']);
    Route::post('/tip', [App\Http\Controllers\Api\WhatsAppBotController::class, 'submitTip']);
});

// WhatsApp Webhook (Meta/WhatsApp Cloud API)
Route::get('/whatsapp/webhook', [App\Http\Controllers\Api\WhatsAppWebhookController::class, 'verify']);
Route::post('/whatsapp/webhook', [App\Http\Controllers\Api\WhatsAppWebhookController::class, 'handle']);

// Public callback for payments (no auth required usually, or verify signature)
Route::post('/v1/payments/callback', [PaymentController::class, 'callback']);
