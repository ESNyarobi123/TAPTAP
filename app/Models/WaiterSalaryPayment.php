<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaiterSalaryPayment extends Model
{
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'period_month',
        'basic_salary',
        'allowances',
        'paye',
        'nssf',
        'net_pay',
        'paid_at',
        'confirmed_by',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:0',
            'allowances' => 'decimal:0',
            'paye' => 'decimal:0',
            'nssf' => 'decimal:0',
            'net_pay' => 'decimal:0',
            'paid_at' => 'datetime',
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

    public function confirmedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function getGrossSalaryAttribute(): float
    {
        return (float) $this->basic_salary + (float) $this->allowances;
    }

    public function getTotalDeductionAttribute(): float
    {
        return (float) $this->paye + (float) $this->nssf;
    }

    public function getPeriodLabelAttribute(): string
    {
        $parts = explode('-', $this->period_month);
        if (count($parts) !== 2) {
            return $this->period_month;
        }
        $months = ['', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
        $m = (int) $parts[1];

        return ($months[$m] ?? $parts[1]).' '.$parts[0];
    }
}
