<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Services\SelcomService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $selcom;

    public function __construct(SelcomService $selcom)
    {
        $this->selcom = $selcom;
    }

    public function ussdRequest(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required|string',
        ]);

        $order = Order::with('restaurant')->find($validated['order_id']);
        $restaurant = $order->restaurant;

        // Check if Selcom is configured
        if (!$restaurant->hasSelcomConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway not configured for this restaurant'
            ], 400);
        }
        
        $transactionRef = 'TXN-' . strtoupper(uniqid());
        
        // Initiate Selcom payment
        $result = $this->selcom->initiatePayment($restaurant->getSelcomCredentials(), [
            'order_id' => $transactionRef,
            'email' => 'customer@taptap.co.tz',
            'name' => 'Customer',
            'phone' => $validated['phone_number'],
            'amount' => $order->total_amount,
            'description' => 'Order #' . $order->id,
        ]);

        if (isset($result['status']) && $result['status'] === 'success') {
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'method' => 'ussd',
                'status' => 'pending',
                'transaction_reference' => $transactionRef,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'USSD push sent',
                'transaction_reference' => $transactionRef,
                'payment_id' => $payment->id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to initiate payment'
        ], 400);
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

            if ($restaurant->hasSelcomConfigured()) {
                $result = $this->selcom->checkOrderStatus(
                    $restaurant->getSelcomCredentials(), 
                    $payment->transaction_reference
                );
                $paymentStatus = $this->selcom->parsePaymentStatus($result);

                if ($paymentStatus === 'paid') {
                    $payment->update(['status' => 'paid']);
                    $order->update(['status' => 'paid']);
                } elseif ($paymentStatus === 'failed') {
                    $payment->update(['status' => 'failed']);
                }
            }
        }

        return response()->json([
            'status' => $payment ? $payment->status : 'unpaid',
            'payment' => $payment
        ]);
    }

    /**
     * Handle Selcom Callback/Webhook
     * This endpoint receives payment status updates from Selcom
     */
    public function callback(Request $request)
    {
        // Log incoming callback for debugging
        Log::info('Selcom Callback Received', $request->all());

        // Get the transaction reference from the callback
        // Selcom uses various fields depending on the callback type
        $orderId = $request->input('order_id') 
            ?? $request->input('transid') 
            ?? $request->input('reference');
        
        $status = $request->input('payment_status') 
            ?? $request->input('result') 
            ?? $request->input('status');
        
        $resultCode = $request->input('resultcode');

        if (!$orderId) {
            Log::warning('Selcom Callback: Order ID missing', $request->all());
            return response()->json(['success' => false, 'message' => 'Order ID missing'], 400);
        }

        // Find the payment by transaction reference
        $payment = Payment::where('transaction_reference', $orderId)->first();

        if (!$payment) {
            Log::warning('Selcom Callback: Payment not found for order_id: ' . $orderId);
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        // Determine payment status based on Selcom response
        $isPaid = false;
        $isFailed = false;

        // Selcom uses resultcode '000' for success
        if ($resultCode === '000') {
            if (strtoupper($status) === 'COMPLETED' || strtoupper($status) === 'SUCCESS') {
                $isPaid = true;
            }
        } elseif (strtoupper($status) === 'FAILED' || strtoupper($status) === 'CANCELLED') {
            $isFailed = true;
        }

        // Update payment status
        if ($isPaid) {
            $payment->update(['status' => 'paid']);
            
            // If this is an order payment, update the order status too
            if ($payment->order_id && $payment->order) {
                $payment->order->update(['status' => 'paid']);
            }

            Log::info('Selcom Callback: Payment marked as paid: ' . $payment->id);
        } elseif ($isFailed) {
            $payment->update(['status' => 'failed']);
            Log::info('Selcom Callback: Payment marked as failed: ' . $payment->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Callback processed successfully',
            'payment_id' => $payment->id,
            'status' => $payment->status
        ]);
    }
}
