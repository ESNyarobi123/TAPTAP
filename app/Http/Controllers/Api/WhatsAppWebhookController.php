<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Webhook Verification (For Meta/WhatsApp Setup)
     * Hii inatumika wakati wa ku-set up bot kwenye Meta Developer Portal
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $verifyToken = config('services.whatsapp.verify_token', 'TAPTAP_BOT_TOKEN');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $verifyToken) {
                return response($challenge, 200);
            }
        }

        return response('Forbidden', 403);
    }

    /**
     * Handle Incoming WhatsApp Messages (The Real Callback)
     * Hapa ndipo meseji za wateja zinapoingia kutoka WhatsApp
     */
    public function handle(Request $request)
    {
        $data = $request->all();

        // Log the incoming message for debugging
        Log::info('WhatsApp Webhook Received:', $data);

        // Hapa utaweka logic ya ku-parse meseji
        // Mfano: Kama ni button click, kama ni text ya jina la restaurant, nk.
        
        /*
        Structure ya Meta Webhook kawaida inakuwa hivi:
        $message = $data['entry'][0]['changes'][0]['value']['messages'][0] ?? null;
        if ($message) {
            $from = $message['from']; // Namba ya mteja
            $text = $message['text']['body'] ?? null; // Meseji aliyoandika
            $buttonPayload = $message['interactive']['button_reply']['id'] ?? null; // Id ya button aliyobonyeza
            
            // Tuma kwenda kwenye WhatsAppBotController au Node.js Bot yako
        }
        */

        return response()->json(['status' => 'success']);
    }
}
