<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Waiter\DashboardController as WaiterDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RestaurantRegistrationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register-restaurant', [RestaurantRegistrationController::class, 'create'])->name('restaurant.register');
Route::post('/register-restaurant', [RestaurantRegistrationController::class, 'store'])->name('restaurant.register.store');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->hasRole('super_admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('manager')) {
        return redirect()->route('manager.dashboard');
    } elseif ($user->hasRole('waiter')) {
        return redirect()->route('waiter.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes removed as ProfileController is not implemented yet
/*
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/

// Admin Portal
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Restaurants
    Route::resource('restaurants', \App\Http\Controllers\Admin\RestaurantController::class);
    Route::post('restaurants/{restaurant}/toggle-status', [\App\Http\Controllers\Admin\RestaurantController::class, 'toggleStatus'])->name('restaurants.toggle-status');
    
    // Users
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Orders
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    
    // Payments
    Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    
    // Withdrawals
    Route::get('withdrawals', [\App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('withdrawals/{withdrawal}/approve', [\App\Http\Controllers\Admin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('withdrawals/{withdrawal}/reject', [\App\Http\Controllers\Admin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');
    
    // Bots
    Route::get('bots', [\App\Http\Controllers\Admin\BotController::class, 'index'])->name('bots.index');
    Route::post('bots/update-endpoint', [\App\Http\Controllers\Admin\BotController::class, 'updateEndpoint'])->name('bots.update-endpoint');
    Route::post('bots/generate-token', [\App\Http\Controllers\Admin\BotController::class, 'generateToken'])->name('bots.generate-token');
    
    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/send', [\App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('notifications.send');
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings/update', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

// Manager Portal
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerDashboard::class, 'index'])->name('dashboard');
    Route::get('/live-orders', [\App\Http\Controllers\Manager\LiveOrderController::class, 'index'])->name('orders.live');
    Route::post('/orders', [\App\Http\Controllers\Manager\LiveOrderController::class, 'store'])->name('orders.store');
    Route::put('/orders/{order}', [\App\Http\Controllers\Manager\LiveOrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [\App\Http\Controllers\Manager\LiveOrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/menu', [\App\Http\Controllers\Manager\MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [\App\Http\Controllers\Manager\MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{menuItem}', [\App\Http\Controllers\Manager\MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menuItem}', [\App\Http\Controllers\Manager\MenuController::class, 'destroy'])->name('menu.destroy');
    
    // Categories
    Route::post('/categories', [\App\Http\Controllers\Manager\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [\App\Http\Controllers\Manager\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Manager\CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/waiters', [\App\Http\Controllers\Manager\WaiterController::class, 'index'])->name('waiters.index');
    Route::post('/waiters', [\App\Http\Controllers\Manager\WaiterController::class, 'store'])->name('waiters.store');
    Route::delete('/waiters/{waiter}', [\App\Http\Controllers\Manager\WaiterController::class, 'destroy'])->name('waiters.destroy');
    Route::get('/payments', [\App\Http\Controllers\Manager\PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/zenopay/initiate', [\App\Http\Controllers\Manager\PaymentController::class, 'initiateZenoPay'])->name('payments.zenopay.initiate');
    Route::get('/payments/zenopay/status/{order}', [\App\Http\Controllers\Manager\PaymentController::class, 'checkZenoPayStatus'])->name('payments.zenopay.status');
    Route::get('/feedback', [\App\Http\Controllers\Manager\FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/tips', [\App\Http\Controllers\Manager\TipController::class, 'index'])->name('tips.index');
    Route::get('/api', [\App\Http\Controllers\Manager\ApiController::class, 'index'])->name('api.index');
    Route::post('/api/zenopay', [\App\Http\Controllers\Manager\ApiController::class, 'updateZenoPayKey'])->name('api.zenopay.update');
    Route::resource('tables', \App\Http\Controllers\Manager\TableController::class);
});

// Waiter Portal
Route::middleware(['auth', 'role:waiter'])->prefix('waiter')->name('waiter.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Waiter\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [\App\Http\Controllers\Waiter\MenuController::class, 'index'])->name('menu');
    Route::get('/orders', [\App\Http\Controllers\Waiter\DashboardController::class, 'orders'])->name('orders');
    Route::get('/tips', [\App\Http\Controllers\Waiter\DashboardController::class, 'tips'])->name('tips');
    Route::get('/ratings', [\App\Http\Controllers\Waiter\DashboardController::class, 'ratings'])->name('ratings');
    Route::post('/requests/{request}/complete', [\App\Http\Controllers\Waiter\DashboardController::class, 'completeRequest'])->name('requests.complete');
    Route::post('/orders/{order}/claim', [\App\Http\Controllers\Waiter\DashboardController::class, 'claimOrder'])->name('orders.claim');
});
