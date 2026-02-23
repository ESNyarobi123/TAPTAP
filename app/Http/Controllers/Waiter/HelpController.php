<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HelpController extends Controller
{
    public function index(): View
    {
        return view('waiter.help.index');
    }
}
