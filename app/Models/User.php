<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'restaurant_id',
        'waiter_code',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'waiter_id');
    }

    public function tips()
    {
        return $this->hasMany(Tip::class, 'waiter_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'waiter_id');
    }

    /**
     * Get WhatsApp QR URL for this waiter
     */
    public function getWaiterQrUrlAttribute()
    {
        if (!$this->restaurant_id) {
            return null;
        }

        $botNumber = \App\Models\Setting::get('whatsapp_bot_number', '255794321510');
        $cleanNumber = preg_replace('/[^0-9]/', '', $botNumber);
        
        // Format: START_{restaurant_id}_W{waiter_id}
        $message = "START_" . $this->restaurant_id . "_W" . $this->id;
        
        return "https://wa.me/" . $cleanNumber . "?text=" . urlencode($message);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
