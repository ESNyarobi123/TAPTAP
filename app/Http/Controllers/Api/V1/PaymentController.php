<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\SelcomService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected SelcomService $selcom;

    public function __construct(SelcomService $selcom)
    {
        $this->selcom = $selcom;
    }

    /**
     * Initiate USSD Push Payment for an Order
     */
    public function ussdRequest(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required|string',
        ]);

        $order = Order::with('restaurant')->find($validated['order_id']);
        $restaurant = $order->restaurant;

        if (! $restaurant || ! $restaurant->hasSelcomConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway not configured for this restaurant',
            ], 400);
        }

        $transactionRef = 'TXN-'.strtoupper(uniqid());

        $result = $this->selcom->initiatePayment($restaurant->getSelcomCredentials(), [
            'order_id' => $transactionRef,
            'email' => 'customer@taptap.co.tz',
            'name' => 'Customer',
            'phone' => $validated['phone_number'],
            'amount' => $order->total_amount,
            'description' => 'Order #'.$order->id,
        ]);

        if (isset($result['status']) && $result['status'] === 'success') {
            $payment = Payment::create([
                'order_id' => $order->id,
                'restaurant_id' => $restaurant->id,
                'customer_phone' => $validated['phone_number'],
                'amount' => $order->total_amount,
                'method' => 'ussd',
                'status' => 'pending',
                'transaction_reference' => $transactionRef,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'USSD push sent to '.$validated['phone_number'],
                'transaction_reference' => $transactionRef,
                'payment_id' => $payment->id,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to initiate payment',
        ], 400);
    }

    /**
     * Record Cash Payment for an Order
     */
    public function cashPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('restaurant')->find($validated['order_id']);

        $payment = Payment::create([
            'order_id' => $order->id,
            'restaurant_id' => $order->restaurant_id,
            'amount' => $order->total_amount,
            'method' => 'cash',
            'status' => 'paid',
            'transaction_reference' => 'CASH-'.strtoupper(uniqid()),
        ]);

        $order->update(['status' => 'paid']);

        return response()->json([
            'success' => true,
            'payment' => $payment,
        ]);
    }

    /**
     * Check Payment Status (Polling)
     * This endpoint is called repeatedly by clients to check payment status
     */
    public function status(Order $order)
    {
        $order->load('restaurant');
        $payment = $order->payments()->where('method', 'ussd')->latest()->first();

        if ($payment && $payment->status === 'pending') {
            $restaurant = $order->restaurant;

            if ($restaurant && $restaurant->hasSelcomConfigured()) {
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
            'success' => true,
            'status' => $payment ? $payment->status : 'unpaid',
            'payment' => $payment,
        ]);
    }
}
