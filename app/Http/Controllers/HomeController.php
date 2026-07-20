<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->with(['category', 'images'])
            ->take(6)
            ->get();

        $categories = ProductCategory::withCount('products')->get();

        $heroBackground = Setting::get('hero_background_home');
        if ($heroBackground && ! str_starts_with($heroBackground, 'http')) {
            $heroBackground = asset('storage/' . $heroBackground);
        }

        return view('pages.home', compact('featuredProducts', 'categories', 'heroBackground'));
    }
}
