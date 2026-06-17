<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->input('search'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->input('category'), fn ($q, $c) => $q->where('product_category_id', $c))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['name']);
        $data['specifications'] = $request->specificationsArray();
        $data['features'] = $request->featuresArray();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['name'], $product->id);
        $data['specifications'] = $request->specificationsArray();
        $data['features'] = $request->featuresArray();

        if ($request->hasFile('image')) {
            if ($product->image && ! str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && ! str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function resolveSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $candidate = $base;
        $i = 1;

        while (Product::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base . '-' . (++$i);
        }

        return $candidate;
    }
}
