<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Carbon\Carbon;

use App\Services\SelcomService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $selcom;

    public function __construct(SelcomService $selcom)
    {
        $this->selcom = $selcom;
    }

    public function index()
    {
        $today = Carbon::today();
        $payments = Payment::whereHas('order')->with('order')->latest()->paginate(15);
        
        $cashRevenue = Payment::whereHas('order')->where('method', 'cash')->whereDate('created_at', $today)->sum('amount');
        $mobileRevenue = Payment::whereHas('order')->where('method', 'ussd')->whereDate('created_at', $today)->sum('amount');
        $cardRevenue = Payment::whereHas('order')->where('method', 'card')->whereDate('created_at', $today)->sum('amount');

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
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        $order = Order::findOrFail($request->order_id);
        $restaurant = Auth::user()->restaurant;

        if (!$restaurant->hasSelcomConfigured()) {
            return response()->json(['status' => 'error', 'message' => 'Selcom payment gateway not configured'], 400);
        }

        $transactionId = 'ORD-' . $order->id . '-' . time();

        $result = $this->selcom->initiatePayment($restaurant->getSelcomCredentials(), [
            'order_id' => $transactionId,
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'amount' => $order->total_amount,
            'description' => 'Order #' . $order->id,
        ]);

        if (isset($result['status']) && $result['status'] === 'success') {
            // Save the external order_id in the order
            $order->update(['payment_reference' => $transactionId]);
            return response()->json($result);
        }

        return response()->json($result, 400);
    }

    /**
     * Check Selcom Payment Status (Polling)
     */
    public function checkSelcomStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        $restaurant = Auth::user()->restaurant;
        $externalId = $order->payment_reference;

        if (!$externalId) {
            return response()->json(['status' => 'error', 'message' => 'No active transaction found'], 400);
        }

        $result = $this->selcom->checkOrderStatus($restaurant->getSelcomCredentials(), $externalId);
        $paymentStatus = $this->selcom->parsePaymentStatus($result);

        if ($paymentStatus === 'paid') {
            // Payment successful, update order status
            $order->update(['status' => 'paid']);
            
            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'method' => 'ussd',
                'status' => 'completed',
            ]);

            return response()->json(['status' => 'paid', 'message' => 'Payment completed successfully!']);
        } elseif ($paymentStatus === 'failed') {
            return response()->json(['status' => 'failed', 'message' => 'Payment failed']);
        }

        return response()->json(['status' => 'pending', 'message' => 'Payment pending...']);
    }
}
