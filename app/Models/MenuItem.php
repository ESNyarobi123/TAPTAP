<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['restaurant_id', 'category_id', 'name', 'description', 'price', 'image', 'is_available', 'preparation_time'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\RestaurantScope);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Image URL for display (works with or without storage:link via storage.serve).
     */
    public function imageUrl(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return route('storage.serve', ['path' => $this->image]);
    }
}
