<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SelcomService
{
    protected string $liveBaseUrl = 'https://apigw.selcommobile.com/v1';

    protected string $sandboxBaseUrl = 'https://apigwtest.selcommobile.com/v1';

    /**
     * Get the base URL based on live/sandbox mode
     */
    protected function getBaseUrl(bool $isLive = false): string
    {
        return $isLive ? $this->liveBaseUrl : $this->sandboxBaseUrl;
    }

    /**
     * Generate authorization headers for Selcom API
     * Selcom requires: Digest = Base64(SHA256(JSON_body))
     * Signature = Base64(HMAC-SHA256(signed_fields_values, api_secret))
     */
    protected function generateHeaders(string $apiKey, string $apiSecret, array $payload = []): array
    {
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');

        // For POST requests, digest is SHA256 of the JSON body
        // For GET requests with no body, use empty string
        $jsonBody = ! empty($payload) ? json_encode($payload) : '';
        $digest = base64_encode(hash('sha256', $jsonBody, true));

        // Signed fields - include timestamp
        $signedFields = 'timestamp';
        $signedData = 'timestamp='.$timestamp;

        // Signature = HMAC-SHA256 of signed fields values
        $signature = base64_encode(hash_hmac('sha256', $signedData, $apiSecret, true));

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
     * Format phone number to 255 format
     */
    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '255'.substr($phone, 1);
        } elseif (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        } elseif (! str_starts_with($phone, '255')) {
            $phone = '255'.$phone;
        }

        return $phone;
    }

    /**
     * Initiate USSD Push payment
     */
    public function initiatePayment(array $credentials, array $data): array
    {
        try {
            $baseUrl = $this->getBaseUrl($credentials['is_live'] ?? false);
            $phone = $this->formatPhone($data['phone']);

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

            // Generate headers with payload for correct digest
            $headers = $this->generateHeaders(
                $credentials['api_key'],
                $credentials['api_secret'],
                $payload
            );

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
    public function initiateUssdPush(array $credentials, array $data): array
    {
        try {
            $baseUrl = $this->getBaseUrl($credentials['is_live'] ?? false);
            $phone = $this->formatPhone($data['phone']);

            $payload = [
                'order_id' => $data['order_id'],
                'msisdn' => $phone,
            ];

            // Generate headers with payload for correct digest
            $headers = $this->generateHeaders(
                $credentials['api_key'],
                $credentials['api_secret'],
                $payload
            );

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
    public function checkOrderStatus(array $credentials, string $orderId): array
    {
        try {
            $baseUrl = $this->getBaseUrl($credentials['is_live'] ?? false);

            // For GET requests, generate headers without payload (empty body)
            $headers = $this->generateHeaders(
                $credentials['api_key'],
                $credentials['api_secret'],
                [] // Empty for GET request
            );

            $response = Http::withHeaders($headers)
                ->get($baseUrl.'/checkout/order-status', [
                    'order_id' => $orderId,
                ]);

            $result = $response->json();

            Log::info('Selcom Order Status Response', [
                'order_id' => $orderId,
                'response' => $result,
            ]);

            return $result ?? [];

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
    public function parsePaymentStatus(array $response): string
    {
        if (! isset($response['resultcode'])) {
            return 'pending';
        }

        if ($response['resultcode'] === '000' && isset($response['data'][0]['payment_status'])) {
            $status = strtoupper($response['data'][0]['payment_status']);

            if (in_array($status, ['COMPLETED', 'SUCCESS', 'SUCCESSFUL'])) {
                return 'paid';
            } elseif (in_array($status, ['FAILED', 'CANCELLED', 'EXPIRED', 'REJECTED'])) {
                return 'failed';
            }
        }

        return 'pending';
    }

    /**
     * Check if credentials are valid
     */
    public function validateCredentials(array $credentials): bool
    {
        return ! empty($credentials['vendor_id'])
            && ! empty($credentials['api_key'])
            && ! empty($credentials['api_secret']);
    }
}
