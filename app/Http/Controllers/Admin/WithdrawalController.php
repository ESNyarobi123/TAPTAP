<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = \App\Models\Withdrawal::with('restaurant')->latest()->paginate(20);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Request $request, string $id)
    {
        $withdrawal = \App\Models\Withdrawal::findOrFail($id);
        
        $withdrawal->update([
            'status' => 'approved',
            'processed_at' => now(),
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Withdrawal request approved.');
    }

    public function reject(Request $request, string $id)
    {
        $withdrawal = \App\Models\Withdrawal::findOrFail($id);
        
        $withdrawal->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Withdrawal request rejected.');
    }
}
