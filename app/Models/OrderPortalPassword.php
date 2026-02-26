<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class OrderPortalPassword extends Model
{
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'password',
        'generated_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->revoked_at === null;
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function checkPassword(string $plain): bool
    {
        return Hash::check($plain, $this->password);
    }

    /**
     * Generate a random password suitable for display once (e.g. 8 alphanumeric).
     */
    public static function generateRandomPassword(): string
    {
        return strtoupper(bin2hex(random_bytes(4)));
    }
}
