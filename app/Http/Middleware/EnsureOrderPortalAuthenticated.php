<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrderPortalAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('order_portal_restaurant_id') || ! session()->has('order_portal_user_id')) {
            return redirect()->route('order-portal.login')
                ->with('error', 'Ingia kwanza kwenye TIPTAP ORDER.');
        }

        return $next($request);
    }
}
