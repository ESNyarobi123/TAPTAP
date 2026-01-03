<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $menuItems = MenuItem::with('category')->latest()->get();
        return view('waiter.menu.index', compact('categories', 'menuItems'));
    }
}
