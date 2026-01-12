<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::latest()->get();
        return view('manager.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $restaurant = Auth::user()->restaurant;

        $table = Table::create([
            'restaurant_id' => $restaurant->id,
            'name' => $request->name,
            'capacity' => $request->capacity ?? 4,
            'is_active' => true,
        ]);

        // Generate QR Code content URL (WhatsApp format)
        $table->update(['qr_code' => $table->whatsapp_qr_url]);

        return back()->with('success', 'Table created successfully!');
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $table->update($data);

        return back()->with('success', 'Table updated successfully!');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return back()->with('success', 'Table deleted successfully!');
    }
}
