<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminSentNotification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'target',
        'restaurant_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function getTargetLabelAttribute(): string
    {
        return match ($this->target) {
            'all' => 'All Users',
            'managers' => 'All Managers',
            'waiters' => 'All Waiters',
            'specific_restaurant' => $this->restaurant?->name ?? 'One Restaurant',
            default => $this->target,
        };
    }
}
