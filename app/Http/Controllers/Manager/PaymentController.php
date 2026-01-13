<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\SelcomService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected SelcomService $selcom;

    public function __construct(SelcomService $selcom)
    {
        $this->selcom = $selcom;
    }

    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        $today = Carbon::today();

        // Get payments for this restaurant only
        $payments = Payment::where('restaurant_id', $restaurant->id)
            ->with('order')
            ->latest()
            ->paginate(15);

        $cashRevenue = Payment::where('restaurant_id', $restaurant->id)
            ->where('method', 'cash')
            ->where('status', 'paid')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $mobileRevenue = Payment::where('restaurant_id', $restaurant->id)
            ->where('method', 'ussd')
            ->where('status', 'paid')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $cardRevenue = Payment::where('restaurant_id', $restaurant->id)
            ->where('method', 'card')
            ->where('status', 'paid')
            ->whereDate('created_at', $today)
            ->sum('amount');

        return view('manager.payments.index', compact('payments', 'cashRevenue', 'mobileRevenue', 'cardRevenue'));
    }

    /**
     * Initiate Selcom USSD Push Payment
     */
    public function initiateSelcom(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'name' => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->order_id);
        $restaurant = Auth::user()->restaurant;

        if (! $restaurant->hasSelcomConfigured()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Selcom payment gateway not configured. Go to API Settings to configure.',
            ], 400);
        }

        $transactionId = 'ORD-'.$order->id.'-'.time();

        $result = $this->selcom->initiatePayment($restaurant->getSelcomCredentials(), [
            'order_id' => $transactionId,
            'email' => $request->email ?? 'customer@taptap.co.tz',
            'name' => $request->name ?? 'Customer',
            'phone' => $request->phone,
            'amount' => $order->total_amount,
            'description' => 'Order #'.$order->id,
        ]);

        if (isset($result['status']) && $result['status'] === 'success') {
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'restaurant_id' => $restaurant->id,
                'customer_phone' => $request->phone,
                'amount' => $order->total_amount,
                'method' => 'ussd',
                'status' => 'pending',
                'transaction_reference' => $transactionId,
            ]);

            // Save the external order_id in the order
            $order->update(['payment_reference' => $transactionId]);

            return response()->json([
                'status' => 'success',
                'message' => 'USSD Push sent to '.$request->phone,
                'payment_id' => $payment->id,
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to initiate payment',
        ], 400);
    }

    /**
     * Check Selcom Payment Status (Polling)
     */
    public function checkSelcomStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        $restaurant = Auth::user()->restaurant;

        // Find the latest pending payment for this order
        $payment = Payment::where('order_id', $order->id)
            ->where('method', 'ussd')
            ->latest()
            ->first();

        if (! $payment || ! $payment->transaction_reference) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active payment transaction found',
            ], 400);
        }

        // If already paid, return immediately
        if ($payment->status === 'paid') {
            return response()->json([
                'status' => 'paid',
                'message' => 'Payment already completed!',
            ]);
        }

        // Check status with Selcom
        $result = $this->selcom->checkOrderStatus(
            $restaurant->getSelcomCredentials(),
            $payment->transaction_reference
        );
        $paymentStatus = $this->selcom->parsePaymentStatus($result);

        if ($paymentStatus === 'paid') {
            $payment->update(['status' => 'paid']);
            $order->update(['status' => 'paid']);

            return response()->json([
                'status' => 'paid',
                'message' => 'Payment completed successfully!',
            ]);
        } elseif ($paymentStatus === 'failed') {
            $payment->update(['status' => 'failed']);

            return response()->json([
                'status' => 'failed',
                'message' => 'Payment failed or cancelled',
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Waiting for payment confirmation...',
        ]);
    }
}
