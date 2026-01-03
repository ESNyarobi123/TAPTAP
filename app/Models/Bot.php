<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $fillable = [
        'name',
        'endpoint',
        'status',
        'last_ping',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'last_ping' => 'datetime',
    ];
}
