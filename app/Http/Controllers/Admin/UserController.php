<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = \App\Models\User::with('restaurant')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = \App\Models\User::with('restaurant')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $restaurants = \App\Models\Restaurant::all();
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.users.edit', compact('user', 'restaurants', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'restaurant_id' => $validated['restaurant_id'],
        ]);

        $user->syncRoles($validated['role']);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
