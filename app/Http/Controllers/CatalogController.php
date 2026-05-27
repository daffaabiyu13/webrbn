<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;

class CatalogController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->get();

        $products = Product::with('category')
            ->when(request('category'), fn ($q, $cat) => $q->where('product_category_id', $cat))
            ->when(request('search'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('pages.catalog.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();

        $related = Product::where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        return view('pages.catalog.show', compact('product', 'related'));
    }
}
