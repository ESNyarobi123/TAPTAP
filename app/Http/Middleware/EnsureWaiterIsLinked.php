<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureWaiterIsLinked
{
    /**
     * Redirect waiters without a linked restaurant to the dashboard (not-linked view).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user || ! $user->hasRole('waiter')) {
            return $next($request);
        }
        if (! $user->isLinkActive()) {
            $user->terminateExpiredLink();

            return redirect()->route('waiter.dashboard');
        }

        return $next($request);
    }
}
