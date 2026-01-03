<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'qr_code', 'capacity', 'is_active'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\RestaurantScope);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
