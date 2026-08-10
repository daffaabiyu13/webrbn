<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Support\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::with('images')
            ->when($request->input('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderBy('position')
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(ArticleRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title']);
        unset($data['images'], $data['delete_images']);

        $article = Article::create($data);

        $this->syncImages($article, $request);

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        $article->load('images');

        return view('admin.articles.edit', compact('article'));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title'], $article->id);
        unset($data['images'], $data['delete_images']);

        $article->update($data);

        $this->syncImages($article, $request);

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        foreach ($article->images as $image) {
            if (! str_starts_with($image->path, 'http')) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil dihapus.');
    }

    private function syncImages(Article $article, ArticleRequest $request): void
    {
        $deleteIds = array_filter((array) $request->input('delete_images', []));
        if ($deleteIds) {
            $toDelete = $article->images()->whereIn('id', $deleteIds)->get();
            foreach ($toDelete as $image) {
                if (! str_starts_with($image->path, 'http')) {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }
        }

        $files = $request->file('images');
        if (! is_array($files) || empty($files)) {
            return;
        }

        $nextPosition = (int) ($article->images()->max('position') ?? -1) + 1;
        $compressor = ImageCompressor::forProducts();

        foreach ($files as $file) {
            if (! $file) {
                continue;
            }
            $path = $compressor->storeCompressed($file, 'public', 'articles');
            ArticleImage::create([
                'article_id' => $article->id,
                'path' => $path,
                'position' => $nextPosition++,
            ]);
        }
    }

    private function resolveSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $title);
        $candidate = $base;
        $i = 1;

        while (Article::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base . '-' . (++$i);
        }

        return $candidate;
    }
}
