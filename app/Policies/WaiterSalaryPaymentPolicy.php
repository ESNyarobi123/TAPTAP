<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaiterSalaryPayment;

class WaiterSalaryPaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->restaurant_id !== null && $user->hasRole('manager');
    }

    public function view(User $user, WaiterSalaryPayment $waiterSalaryPayment): bool
    {
        if ($user->hasRole('waiter')) {
            return (int) $waiterSalaryPayment->user_id === (int) $user->id;
        }
        if ($user->hasRole('manager') && $user->restaurant_id !== null) {
            return (int) $waiterSalaryPayment->restaurant_id === (int) $user->restaurant_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->restaurant_id !== null && $user->hasRole('manager');
    }

    public function update(User $user, WaiterSalaryPayment $waiterSalaryPayment): bool
    {
        return $user->restaurant_id !== null
            && $user->hasRole('manager')
            && (int) $waiterSalaryPayment->restaurant_id === (int) $user->restaurant_id;
    }

    public function delete(User $user, WaiterSalaryPayment $waiterSalaryPayment): bool
    {
        return false;
    }
}
