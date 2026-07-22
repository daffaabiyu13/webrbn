<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Support\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'images'])
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
        $data['specifications'] = $request->specificationsArray('specifications');
        $data['specifications_en'] = $request->specificationsArray('specifications_en');
        $data['features'] = $request->featuresArray('features');
        $data['features_en'] = $request->featuresArray('features_en');

        unset($data['images'], $data['delete_images']);

        $product = Product::create($data);

        $this->syncImages($product, $request);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['name'], $product->id);
        $data['specifications'] = $request->specificationsArray('specifications');
        $data['specifications_en'] = $request->specificationsArray('specifications_en');
        $data['features'] = $request->featuresArray('features');
        $data['features_en'] = $request->featuresArray('features_en');

        unset($data['images'], $data['delete_images']);

        $product->update($data);

        $this->syncImages($product, $request);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if (! str_starts_with($image->path, 'http')) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function syncImages(Product $product, ProductRequest $request): void
    {
        // 1. Delete images the user marked
        $deleteIds = array_filter((array) $request->input('delete_images', []));
        if ($deleteIds) {
            $toDelete = $product->images()->whereIn('id', $deleteIds)->get();
            foreach ($toDelete as $image) {
                if (! str_starts_with($image->path, 'http')) {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }
        }

        // 2. Append newly uploaded images
        $files = $request->file('images');
        if (! is_array($files) || empty($files)) {
            return;
        }

        $nextPosition = (int) ($product->images()->max('position') ?? -1) + 1;
        $compressor = ImageCompressor::forProducts();

        foreach ($files as $file) {
            if (! $file) {
                continue;
            }
            $path = $compressor->storeCompressed($file, 'public', 'products');
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'position' => $nextPosition++,
            ]);
        }
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
