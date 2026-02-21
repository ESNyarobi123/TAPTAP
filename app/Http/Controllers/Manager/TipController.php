<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

class TipController extends Controller
{
    /**
     * Tips are not shown to manager. Redirect to dashboard.
     */
    public function index()
    {
        return redirect()->route('manager.dashboard')->with('info', 'Tips are not visible to managers.');
    }
}
