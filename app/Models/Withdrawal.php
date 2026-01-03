<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'restaurant_id',
        'amount',
        'status',
        'payment_method',
        'payment_details',
        'processed_at',
        'admin_note',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
