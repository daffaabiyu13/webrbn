<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Support\HeroStyle;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->with(['category', 'images'])
            ->take(6)
            ->get();

        $categories = ProductCategory::withCount('products')->get();

        $hero = HeroStyle::forPage('home');

        return view('pages.home', compact('featuredProducts', 'categories', 'hero'));
    }
}
