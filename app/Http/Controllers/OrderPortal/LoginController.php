<?php

namespace App\Http\Controllers\OrderPortal;

use App\Http\Controllers\Controller;
use App\Models\OrderPortalPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('order-portal.login');
    }

    /**
     * Login with password only. Password is unique per waiter/restaurant;
     * system identifies which restaurant (and waiter) from the password.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|max:50',
        ]);

        $plain = $request->password;

        $credential = OrderPortalPassword::query()
            ->whereNull('revoked_at')
            ->with('user')
            ->get()
            ->first(fn ($c) => $c->checkPassword($plain));

        if (! $credential) {
            return back()->with('error', 'Password si sahihi au imekwisha tamaa. Omba mpya kwa manager wako.');
        }

        $user = $credential->user;
        if (! $user->hasRole('waiter') || $user->restaurant_id != $credential->restaurant_id) {
            return back()->with('error', 'Huna ufikiaji wa Order Portal. Wasiliana na manager wako.');
        }

        session([
            'order_portal_restaurant_id' => $credential->restaurant_id,
            'order_portal_user_id' => $user->id,
        ]);

        return redirect()->route('order-portal.orders')->with('success', 'Umefanikiwa kuingia.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        session()->forget(['order_portal_restaurant_id', 'order_portal_user_id']);

        return redirect()->route('order-portal.login')->with('success', 'Umetoka.');
    }
}
