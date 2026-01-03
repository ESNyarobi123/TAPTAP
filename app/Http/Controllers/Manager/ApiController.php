<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        return view('manager.api.index', compact('restaurant'));
    }

    public function updateZenoPayKey(Request $request)
    {
        $request->validate([
            'zenopay_api_key' => 'required|string|max:255',
        ]);

        $restaurant = Auth::user()->restaurant;
        $restaurant->update([
            'zenopay_api_key' => $request->zenopay_api_key,
        ]);

        return back()->with('success', 'ZenoPay API Key updated successfully!');
    }
}
