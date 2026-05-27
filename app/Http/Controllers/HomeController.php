<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->with('category')
            ->take(6)
            ->get();

        $categories = ProductCategory::withCount('products')->get();

        return view('pages.home', compact('featuredProducts', 'categories'));
    }
}
