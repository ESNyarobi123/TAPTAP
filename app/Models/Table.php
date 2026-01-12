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
    
    public function getWhatsappQrUrlAttribute()
    {
        $botNumber = \App\Models\Setting::get('whatsapp_bot_number', '255794321510');
        // Strip non-numeric characters
        $cleanNumber = preg_replace('/[^0-9]/', '', $botNumber);
        
        $message = "START_" . $this->restaurant_id . "_" . $this->id;
        
        return "https://wa.me/" . $cleanNumber . "?text=" . urlencode($message);
    }
}
