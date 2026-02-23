<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HelpController extends Controller
{
    public function index(): View
    {
        return view('manager.help.index');
    }
}
