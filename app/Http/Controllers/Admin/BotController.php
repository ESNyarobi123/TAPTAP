<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index()
    {
        $bots = \App\Models\Bot::all();
        $botToken = env('BOT_TOKEN');
        return view('admin.bots.index', compact('bots', 'botToken'));
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

    public function generateToken(Request $request)
    {
        // 1. Ensure Bot User exists
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'bot@taptap.com'],
            [
                'name' => 'WhatsApp Bot Service',
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
            ]
        );

        // 2. Ensure Role exists and is assigned
        if (!$user->hasRole('bot_service')) {
            $user->assignRole('bot_service');
        }

        // 3. Generate New Token
        $user->tokens()->delete(); // Clear old tokens
        $token = $user->createToken('WhatsAppBotToken')->plainTextToken;

        // 4. Update .env file
        $this->updateEnv('BOT_TOKEN', $token);

        return back()->with('success', 'New Bot Token generated and saved to .env successfully!');
    }

    private function updateEnv($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            if (str_contains($content, "{$key}=")) {
                // Update existing key
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                // Add new key
                $content .= "\n{$key}={$value}";
            }

            file_put_contents($path, $content);
        }
    }
}
