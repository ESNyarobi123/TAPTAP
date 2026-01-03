<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Carbon\Carbon;

use App\Services\ZenoPayService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $zenoPay;

    public function __construct(ZenoPayService $zenoPay)
    {
        $this->zenoPay = $zenoPay;
    }

    public function index()
    {
        $today = Carbon::today();
        $payments = Payment::with('order')->latest()->paginate(15);
        
        $cashRevenue = Payment::where('method', 'cash')->whereDate('created_at', $today)->sum('amount');
        $mobileRevenue = Payment::where('method', 'ussd')->whereDate('created_at', $today)->sum('amount');
        $cardRevenue = Payment::where('method', 'card')->whereDate('created_at', $today)->sum('amount');

        return view('manager.payments.index', compact('payments', 'cashRevenue', 'mobileRevenue', 'cardRevenue'));
    }

    public function initiateZenoPay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        $order = Order::findOrFail($request->order_id);
        $restaurant = Auth::user()->restaurant;

        if (!$restaurant->zenopay_api_key) {
            return response()->json(['status' => 'error', 'message' => 'ZenoPay API Key not configured'], 400);
        }

        $data = [
            'order_id' => 'ORD-' . $order->id . '-' . time(),
            'buyer_email' => $request->email,
            'buyer_name' => $request->name,
            'buyer_phone' => $request->phone,
            'amount' => $order->total_amount,
        ];

        $result = $this->zenoPay->initiatePayment($restaurant->zenopay_api_key, $data);

        if (isset($result['status']) && $result['status'] === 'success') {
            // Save the external order_id in the order
            $order->update(['payment_reference' => $data['order_id']]);
            return response()->json($result);
        }

        return response()->json($result, 400);
    }

    public function checkZenoPayStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        $restaurant = Auth::user()->restaurant;
        $externalId = $order->payment_reference; // Retrieve the external ID we saved

        if (!$externalId) {
            return response()->json(['status' => 'error', 'message' => 'No active transaction found'], 400);
        }

        $result = $this->zenoPay->checkStatus($restaurant->zenopay_api_key, $externalId);

        if (isset($result['result']) && $result['result'] === 'SUCCESS') {
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
        }

        return response()->json($result);
    }
}
