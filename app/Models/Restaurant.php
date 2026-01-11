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
    protected $fillable = ['name', 'location', 'phone', 'logo', 'menu_image', 'is_active', 'zenopay_api_key', 'kitchen_token', 'kitchen_token_generated_at'];

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
}
