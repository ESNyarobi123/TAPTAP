<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant; // Added for restaurant relationship
use App\Models\User;       // Added for waiter relationship (assuming User model for waiters)
use App\Models\Order;      // Added for order relationship

class Tip extends Model
{
    protected $fillable = ['restaurant_id', 'waiter_id', 'order_id', 'amount'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\RestaurantScope);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
