<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'total_products' => Product::where('user_id', $userId)->count(),
            'total_categories' => Category::where('user_id', $userId)->count(),
            'active_products' => Product::where('user_id', $userId)->where('is_active', true)->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
