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

    /**
     * Update Selcom Payment Gateway Credentials
     */
    public function updateSelcomCredentials(Request $request)
    {
        $request->validate([
            'selcom_vendor_id' => 'required|string|max:255',
            'selcom_api_key' => 'required|string|max:255',
            'selcom_api_secret' => 'required|string|max:255',
            'selcom_is_live' => 'nullable|boolean',
        ]);

        $restaurant = Auth::user()->restaurant;
        $restaurant->update([
            'selcom_vendor_id' => $request->selcom_vendor_id,
            'selcom_api_key' => $request->selcom_api_key,
            'selcom_api_secret' => $request->selcom_api_secret,
            'selcom_is_live' => $request->has('selcom_is_live'),
        ]);

        return back()->with('success', 'Selcom credentials updated successfully!');
    }
}
