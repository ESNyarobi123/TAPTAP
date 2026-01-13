<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SelcomService
{
    protected $liveBaseUrl = 'https://apigw.selcommobile.com/v1';

    protected $sandboxBaseUrl = 'https://apigwtest.selcommobile.com/v1';

    /**
     * Get the base URL based on live/sandbox mode
     */
    protected function getBaseUrl($isLive = false)
    {
        return $isLive ? $this->liveBaseUrl : $this->sandboxBaseUrl;
    }

    /**
     * Generate authorization headers for Selcom API
     */
    protected function generateHeaders($apiKey, $apiSecret)
    {
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $signedFields = 'timestamp';
        $digest = base64_encode(hash('sha256', $timestamp, true));
        $signature = base64_encode(hash_hmac('sha256', "timestamp=$timestamp", $apiSecret, true));

        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'SELCOM '.base64_encode($apiKey),
            'Digest-Method' => 'HS256',
            'Digest' => $digest,
            'Timestamp' => $timestamp,
            'Signed-Fields' => $signedFields,
            'Signature' => $signature,
        ];
    }

    /**
     * Initiate USSD Push payment
     */
    public function initiatePayment($credentials, $data)
    {
        try {
            $baseUrl = $this->getBaseUrl($credentials['is_live'] ?? false);
            $headers = $this->generateHeaders($credentials['api_key'], $credentials['api_secret']);

            // Format phone number (remove + and ensure starts with 255)
            $phone = preg_replace('/[^0-9]/', '', $data['phone']);
            if (substr($phone, 0, 1) === '0') {
                $phone = '255'.substr($phone, 1);
            }

            $payload = [
                'vendor' => $credentials['vendor_id'],
                'order_id' => $data['order_id'],
                'buyer_email' => $data['email'] ?? 'customer@taptap.co.tz',
                'buyer_name' => $data['name'] ?? 'Customer',
                'buyer_phone' => $phone,
                'amount' => (int) $data['amount'],
                'currency' => 'TZS',
                'buyer_remarks' => $data['description'] ?? 'Payment',
                'merchant_remarks' => $data['description'] ?? 'Payment',
                'no_of_items' => 1,
            ];

            Log::info('Selcom Payment Request', [
                'url' => $baseUrl.'/checkout/create-order-minimal',
                'payload' => $payload,
            ]);

            $response = Http::withHeaders($headers)
                ->post($baseUrl.'/checkout/create-order-minimal', $payload);

            $result = $response->json();

            Log::info('Selcom Payment Response', ['response' => $result]);

            if (isset($result['resultcode']) && $result['resultcode'] === '000') {
                // Now initiate USSD Push
                $ussdResult = $this->initiateUssdPush($credentials, [
                    'order_id' => $data['order_id'],
                    'phone' => $phone,
                ]);

                return [
                    'status' => 'success',
                    'order_id' => $data['order_id'],
                    'transid' => $result['data'][0]['transid'] ?? null,
                    'message' => 'USSD Push sent to '.$phone,
                    'ussd_result' => $ussdResult,
                ];
            }

            return [
                'status' => 'error',
                'message' => $result['message'] ?? 'Failed to create order',
                'raw' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('Selcom Payment Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Connection failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Initiate USSD Push for existing order
     */
    public function initiateUssdPush($credentials, $data)
    {
        try {
            $baseUrl = $this->getBaseUrl($credentials['is_live'] ?? false);
            $headers = $this->generateHeaders($credentials['api_key'], $credentials['api_secret']);

            // Format phone number
            $phone = preg_replace('/[^0-9]/', '', $data['phone']);
            if (substr($phone, 0, 1) === '0') {
                $phone = '255'.substr($phone, 1);
            }

            $payload = [
                'order_id' => $data['order_id'],
                'msisdn' => $phone,
            ];

            $response = Http::withHeaders($headers)
                ->post($baseUrl.'/checkout/wallet-payment', $payload);

            $result = $response->json();

            Log::info('Selcom USSD Push Response', ['response' => $result]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Selcom USSD Push Error: '.$e->getMessage());

            return [
                'status' => 'error',
                'message' => 'USSD Push failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check order/payment status (for polling)
     */
    public function checkOrderStatus($credentials, $orderId)
    {
        try {
            $baseUrl = $this->getBaseUrl($credentials['is_live'] ?? false);
            $headers = $this->generateHeaders($credentials['api_key'], $credentials['api_secret']);

            $response = Http::withHeaders($headers)
                ->get($baseUrl.'/checkout/order-status', [
                    'order_id' => $orderId,
                ]);

            $result = $response->json();

            Log::info('Selcom Order Status Response', [
                'order_id' => $orderId,
                'response' => $result,
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Selcom Status Check Error: '.$e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Status check failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Parse payment status from Selcom response
     */
    public function parsePaymentStatus($response)
    {
        if (! isset($response['resultcode'])) {
            return 'pending';
        }

        if ($response['resultcode'] === '000' && isset($response['data'][0]['payment_status'])) {
            $status = strtoupper($response['data'][0]['payment_status']);

            if ($status === 'COMPLETED' || $status === 'SUCCESS') {
                return 'paid';
            } elseif ($status === 'FAILED' || $status === 'CANCELLED') {
                return 'failed';
            }
        }

        return 'pending';
    }

    /**
     * Check if credentials are valid
     */
    public function validateCredentials($credentials)
    {
        return ! empty($credentials['vendor_id'])
            && ! empty($credentials['api_key'])
            && ! empty($credentials['api_secret']);
    }
}
