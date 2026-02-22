<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaiterRestaurantAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'linked_at',
        'unlinked_at',
        'employment_type',
        'linked_until',
    ];

    protected function casts(): array
    {
        return [
            'linked_at' => 'datetime',
            'unlinked_at' => 'datetime',
            'linked_until' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function isActive(): bool
    {
        return $this->unlinked_at === null;
    }
}
