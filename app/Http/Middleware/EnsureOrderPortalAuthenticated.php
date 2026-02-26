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
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Ingia kwanza kwenye TIPTAP ORDER.',
                    'error' => 'unauthenticated',
                ], 401);
            }

            return redirect()->route('order-portal.login')
                ->with('error', 'Ingia kwanza kwenye TIPTAP ORDER.');
        }

        return $next($request);
    }
}
