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
        $payment = $order->payments()->latest()->first();
        return response()->json([
            'status' => $payment ? $payment->status : 'unpaid',
            'payment' => $payment
        ]);
    }

    public function callback(Request $request)
    {
        // Handle gateway callback
        // Example: verify signature, find payment by reference, update status
        
        $reference = $request->input('transaction_reference');
        $status = $request->input('status'); // success, failed

        $payment = Payment::where('transaction_reference', $reference)->first();

        if ($payment) {
            if ($status === 'success') {
                $payment->update(['status' => 'paid']);
                $payment->order->update(['status' => 'paid']);
            } else {
                $payment->update(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'received']);
    }
}
