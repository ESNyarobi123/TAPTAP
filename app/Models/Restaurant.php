<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Feedback;
use App\Models\Tip;

class Restaurant extends Model
{
    protected $fillable = [
        'name', 
        'location', 
        'phone', 
        'logo', 
        'menu_image', 
        'is_active', 
        'selcom_vendor_id',
        'selcom_api_key',
        'selcom_api_secret',
        'selcom_is_live',
        'kitchen_token', 
        'kitchen_token_generated_at'
    ];

    /**
     * Get Selcom credentials array
     */
    public function getSelcomCredentials()
    {
        return [
            'vendor_id' => $this->selcom_vendor_id,
            'api_key' => $this->selcom_api_key,
            'api_secret' => $this->selcom_api_secret,
            'is_live' => $this->selcom_is_live,
        ];
    }

    /**
     * Check if Selcom is configured
     */
    public function hasSelcomConfigured()
    {
        return !empty($this->selcom_vendor_id) 
            && !empty($this->selcom_api_key) 
            && !empty($this->selcom_api_secret);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function tips()
    {
        return $this->hasMany(Tip::class);
    }

    public function getWhatsappQrUrlAttribute()
    {
        $botNumber = \App\Models\Setting::get('whatsapp_bot_number', '255794321510');
        // Strip non-numeric characters
        $cleanNumber = preg_replace('/[^0-9]/', '', $botNumber);
        
        $message = "START_" . $this->id;
        
        return "https://wa.me/" . $cleanNumber . "?text=" . urlencode($message);
    }
}
