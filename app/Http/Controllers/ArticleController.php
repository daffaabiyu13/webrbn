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
}
