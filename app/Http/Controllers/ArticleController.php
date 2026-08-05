<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Support\HeroStyle;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('is_published', true)
            ->orderBy('position')
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->paginate(9);

        $hero = HeroStyle::forPage('articles');

        return view('pages.articles.index', compact('articles', 'hero'));
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = Article::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->orderBy('position')
            ->orderByDesc('year')
            ->take(3)
            ->get();

        return view('pages.articles.show', compact('article', 'related'));
    }
}
