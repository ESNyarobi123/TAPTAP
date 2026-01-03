<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = \App\Models\Payment::with(['order.restaurant'])->latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    public function show(string $id)
    {
        $payment = \App\Models\Payment::with(['order.restaurant'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }
}
