<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        if (! $request->has('demo_push')) {
            $data['demo_push'] = '0';
        }

        foreach ($data as $key => $value) {
            $payload = ['value' => $value];
            if ($key === 'demo_push') {
                $payload['group'] = 'payments';
            }
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                $payload
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
