<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;

class CatalogController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->get();

        $products = Product::with(['category', 'images'])
            ->when(request('category'), fn ($q, $cat) => $q->where('product_category_id', $cat))
            ->when(request('search'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        $heroBackground = $this->heroBackground();

        return view('pages.catalog.index', compact('products', 'categories', 'heroBackground'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'images'])->where('slug', $slug)->firstOrFail();

        $related = Product::with('images')
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        return view('pages.catalog.show', compact('product', 'related'));
    }

    private function heroBackground(): ?string
    {
        $path = Setting::get('hero_background_catalog');
        if ($path && ! str_starts_with($path, 'http')) {
            return asset('storage/' . $path);
        }

        return $path;
    }
}
