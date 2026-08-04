<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\HeroStyle;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_published', true)
            ->orderBy('position')
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->paginate(9);

        $hero = HeroStyle::forPage('about');

        return view('pages.projects.index', compact('projects', 'hero'));
    }
}
