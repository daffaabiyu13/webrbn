<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'featured' => Product::where('is_featured', true)->count(),
            'categories' => ProductCategory::count(),
        ];

        $recentProducts = Product::with(['category', 'images'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentProducts'));
    }
}
