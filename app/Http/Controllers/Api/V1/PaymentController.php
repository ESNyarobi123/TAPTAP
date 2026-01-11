<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    public function ussdRequest(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required|string',
        ]);

        $order = Order::find($validated['order_id']);
        
        // Simulate USSD request to gateway
        $transactionRef = 'TXN-' . strtoupper(uniqid());
        
        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'method' => 'ussd',
            'status' => 'pending',
            'transaction_reference' => $transactionRef,
        ]);

        // In real app, call gateway API here

        return response()->json([
            'message' => 'USSD push sent',
            'transaction_reference' => $transactionRef,
            'payment_id' => $payment->id
        ]);
    }

    public function cashPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'method' => 'cash',
            'status' => 'paid', // Cash is immediate
            'transaction_reference' => 'CASH-' . strtoupper(uniqid()),
        ]);

        $order->update(['status' => 'paid']);

        return response()->json($payment);
    }

    public function status(Order $order)
    {
        $payment = $order->payments()->where('method', 'ussd')->latest()->first();
        
        if ($payment && $payment->status === 'pending') {
            $restaurant = $order->restaurant;
            $apiKey = $restaurant->zenopay_api_key;

            if ($apiKey) {
                $zenoPay = new \App\Services\ZenoPayService();
                $result = $zenoPay->checkStatus($apiKey, $payment->transaction_reference);

                if (isset($result['payment_status'])) {
                    if ($result['payment_status'] === 'COMPLETED' || $result['payment_status'] === 'SUCCESS') {
                        $payment->update(['status' => 'paid']);
                        $order->update(['status' => 'paid']);
                    } elseif ($result['payment_status'] === 'FAILED') {
                        $payment->update(['status' => 'failed']);
                    }
                }
            }
        }

        return response()->json([
            'status' => $payment ? $payment->status : 'unpaid',
            'payment' => $payment
        ]);
    }

    /**
     * Handle ZenoPay Callback/Webhook
     * This endpoint receives payment status updates from ZenoPay
     */
    public function callback(Request $request)
    {
        // Log incoming callback for debugging
        \Illuminate\Support\Facades\Log::info('ZenoPay Callback Received', $request->all());

        // Get the transaction reference from the callback
        $orderId = $request->input('order_id'); // This is our transaction_reference
        $status = $request->input('payment_status') ?? $request->input('status');
        $resultCode = $request->input('result') ?? $request->input('result_code');

        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Order ID missing'], 400);
        }

        // Find the payment by transaction reference
        $payment = Payment::where('transaction_reference', $orderId)->first();

        if (!$payment) {
            \Illuminate\Support\Facades\Log::warning('Payment not found for order_id: ' . $orderId);
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        // Determine payment status
        $isPaid = false;
        $isFailed = false;

        if ($status === 'COMPLETED' || $status === 'SUCCESS' || $resultCode === 'SUCCESS') {
            $isPaid = true;
        } elseif ($status === 'FAILED' || $resultCode === 'FAILED') {
            $isFailed = true;
        }

        // Update payment status
        if ($isPaid) {
            $payment->update(['status' => 'paid']);
            
            // If this is an order payment, update the order status too
            if ($payment->order_id && $payment->order) {
                $payment->order->update(['status' => 'paid']);
            }

            \Illuminate\Support\Facades\Log::info('Payment marked as paid: ' . $payment->id);
        } elseif ($isFailed) {
            $payment->update(['status' => 'failed']);
            \Illuminate\Support\Facades\Log::info('Payment marked as failed: ' . $payment->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Callback processed successfully',
            'payment_id' => $payment->id,
            'status' => $payment->status
        ]);
    }
}
