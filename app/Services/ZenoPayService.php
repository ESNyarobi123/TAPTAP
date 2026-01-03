<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZenoPayService
{
    protected $baseUrl = 'https://zenoapi.com/api/payments';

    public function initiatePayment($apiKey, $data)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/mobile_money_tanzania', [
                'order_id' => $data['order_id'],
                'buyer_email' => $data['buyer_email'],
                'buyer_name' => $data['buyer_name'],
                'buyer_phone' => $data['buyer_phone'],
                'amount' => $data['amount'],
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ZenoPay Initiation Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Connection failed'];
        }
    }

    public function checkStatus($apiKey, $orderId)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
            ])->get($this->baseUrl . '/order-status', [
                'order_id' => $orderId,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ZenoPay Status Check Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Connection failed'];
        }
    }
}
