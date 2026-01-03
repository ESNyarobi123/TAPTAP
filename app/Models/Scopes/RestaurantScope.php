<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class RestaurantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();

            // If user has a restaurant_id, scope the query
            if ($user->restaurant_id) {
                $builder->where('restaurant_id', $user->restaurant_id);
            }
            
            // If user is super admin (and presumably doesn't have restaurant_id or we want them to see all), 
            // we don't apply the scope. 
            // Note: If super admin HAS a restaurant_id (e.g. testing), they will be scoped. 
            // Usually super admin has restaurant_id = null.
        }
    }
}
