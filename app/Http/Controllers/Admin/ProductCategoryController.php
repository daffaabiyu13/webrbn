<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::withCount('products')
            ->when($request->input('search'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(ProductCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['name']);

        ProductCategory::create($data);

        return redirect()->route('admin.categories.index')->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function edit(ProductCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(ProductCategoryRequest $request, ProductCategory $category)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['name'], $category->id);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ProductCategory $category)
    {
        $productCount = $category->products()->count();
        if ($productCount > 0) {
            return redirect()->route('admin.categories.index')
                ->withErrors(['delete' => "Kategori \"{$category->name}\" masih memiliki {$productCount} produk. Pindahkan atau hapus produk-produk itu dulu."]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Kategori berhasil dihapus.');
    }

    private function resolveSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $candidate = $base;
        $i = 1;

        while (ProductCategory::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base . '-' . (++$i);
        }

        return $candidate;
    }
}
