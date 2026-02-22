<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkWaiterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaiterController extends Controller
{
    public function index()
    {
        $restaurantId = Auth::user()->restaurant_id;
        $waiters = User::role('waiter')
            ->activeAtRestaurant($restaurantId)
            ->withCount('orders')
            ->get();

        return view('manager.waiters.index', compact('waiters'));
    }

    /**
     * Search waiter by global number (for linking).
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|max:30']);

        $q = strtoupper(trim($request->q));

        $waiter = User::role('waiter')
            ->where('global_waiter_number', $q)
            ->withCount(['orders', 'feedback'])
            ->with('restaurant:id,name')
            ->first();

        if (! $waiter) {
            return response()->json(['success' => false, 'message' => 'Waiter hajapatikana. Angalia nambari ya pekee (TIPTAP-W-xxxxx).']);
        }

        return response()->json([
            'success' => true,
            'waiter' => [
                'id' => $waiter->id,
                'name' => $waiter->name,
                'email' => $waiter->email,
                'phone' => $waiter->phone,
                'location' => $waiter->location,
                'global_waiter_number' => $waiter->global_waiter_number,
                'orders_count' => $waiter->orders_count,
                'feedback_count' => $waiter->feedback_count,
                'current_restaurant' => $waiter->restaurant?->name,
                'is_linked' => (bool) $waiter->restaurant_id,
            ],
        ]);
    }

    /**
     * Link waiter to current manager's restaurant (permanent or temporary / show-time).
     */
    public function link(LinkWaiterRequest $request, User $waiter): RedirectResponse
    {
        if (! $waiter->hasRole('waiter')) {
            return back()->with('error', 'User si waiter.');
        }

        if ($waiter->restaurant_id !== null) {
            return back()->with('error', 'Waiter tayari ameunganishwa na restaurant nyingine. Manager wa restaurant ile anafaa kum-unlink kwanza.');
        }

        $restaurant = Auth::user()->restaurant;
        if (! $restaurant || ! $restaurant->tag_prefix) {
            return back()->with('error', 'Restaurant yako haijaweka tag prefix. Wasiliana na msaada.');
        }

        $waiter->restaurant_id = $restaurant->id;
        $waiter->waiter_code = $restaurant->generateWaiterCode();
        $waiter->employment_type = $request->validated('employment_type');
        $waiter->linked_until = $request->validated('employment_type') === 'temporary'
            ? $request->validated('linked_until')
            : null;
        $waiter->save();

        $msg = "Waiter {$waiter->name} ameunganishwa na restaurant yako. Code: {$waiter->waiter_code}";
        if ($waiter->employment_type === 'temporary' && $waiter->linked_until) {
            $msg .= ' (muda mpaka '.$waiter->linked_until->format('d/m/Y').')';
        }

        return back()->with('success', $msg);
    }

    /**
     * Unlink waiter from current manager's restaurant (history is preserved).
     */
    public function unlink(User $waiter): RedirectResponse
    {
        if ($waiter->restaurant_id !== Auth::user()->restaurant_id || ! $waiter->hasRole('waiter')) {
            return back()->with('error', 'Unauthorized.');
        }

        $name = $waiter->name;
        $waiter->restaurant_id = null;
        $waiter->waiter_code = null;
        $waiter->employment_type = null;
        $waiter->linked_until = null;
        $waiter->save();

        return back()->with('success', "{$name} ameondolewa kwenye restaurant yako. History yake (orders, ratings) imebaki. Anaweza kuungwa na restaurant nyingine.");
    }
}
