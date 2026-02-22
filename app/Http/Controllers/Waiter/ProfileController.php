<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Waiter\UpdateWaiterProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(UpdateWaiterProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if ($request->filled('name')) {
            $user->name = $request->validated('name');
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        $msg = 'Profile updated.';
        if ($request->hasFile('profile_photo')) {
            $msg = 'Profile picture updated.';
        } elseif ($request->filled('name')) {
            $msg = 'Name updated.';
        }

        return back()->with('success', $msg);
    }
}
