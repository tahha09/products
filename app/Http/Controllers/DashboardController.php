<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized access');
        }

        // Statistics based on user role
        if ($user->isAdmin()) {
            // Admin sees all data
            $stats = [
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
                'active_products' => Product::where('is_active', true)->count(),
            ];
        } elseif ($user->isManager()) {
            // Manager sees only active products and categories
            $stats = [
                'total_products' => Product::where('is_active', true)->count(),
                'total_categories' => Category::where('is_active', true)->count(),
                'active_products' => Product::where('is_active', true)->count(),
            ];
        } else {
            // Regular user sees only their own data
            $stats = [
                'total_products' => Product::where('user_id', $user->id)->count(),
                'total_categories' => Category::where('user_id', $user->id)->count(),
                'active_products' => Product::where('user_id', $user->id)
                    ->where('is_active', true)
                    ->count(),
            ];
        }

        return view('dashboard', compact('stats'));
    }
}
