<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Withdrawal::with('restaurant')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(20)->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Request $request, string $id)
    {
        $withdrawal = \App\Models\Withdrawal::findOrFail($id);
        $oldStatus = $withdrawal->status;

        $withdrawal->update([
            'status' => 'approved',
            'processed_at' => now(),
            'admin_note' => $request->admin_note,
        ]);

        \App\Models\AdminActivityLog::log(
            'withdrawal.approved',
            'withdrawal',
            (int) $withdrawal->id,
            ['status' => $oldStatus, 'amount' => $withdrawal->amount],
            ['status' => 'approved', 'processed_at' => $withdrawal->processed_at?->toIso8601String()],
            ['admin_note' => $request->admin_note]
        );

        return back()->with('success', 'Withdrawal request approved.');
    }

    public function reject(Request $request, string $id)
    {
        $withdrawal = \App\Models\Withdrawal::findOrFail($id);
        $oldStatus = $withdrawal->status;

        $withdrawal->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'admin_note' => $request->admin_note,
        ]);

        \App\Models\AdminActivityLog::log(
            'withdrawal.rejected',
            'withdrawal',
            (int) $withdrawal->id,
            ['status' => $oldStatus, 'amount' => $withdrawal->amount],
            ['status' => 'rejected', 'processed_at' => $withdrawal->processed_at?->toIso8601String()],
            ['admin_note' => $request->admin_note]
        );

        return back()->with('success', 'Withdrawal request rejected.');
    }
}
