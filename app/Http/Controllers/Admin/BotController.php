<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index()
    {
        $bots = \App\Models\Bot::all();
        return view('admin.bots.index', compact('bots'));
    }

    public function updateEndpoint(Request $request)
    {
        $validated = $request->validate([
            'bot_id' => 'required|exists:bots,id',
            'endpoint' => 'required|url',
        ]);

        $bot = \App\Models\Bot::findOrFail($validated['bot_id']);
        $bot->update(['endpoint' => $validated['endpoint']]);

        return back()->with('success', 'Bot endpoint updated successfully.');
    }
}
