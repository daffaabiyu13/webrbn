<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\HeroStyle;

class ProjectController extends Controller
{
    public function index()
    {
        $hero = HeroStyle::forPage('projects');

        $projects = Project::orderByDesc('is_featured')
            ->orderBy('position')
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->get();

        $categories = $projects
            ->map(fn ($p) => $p->translated('category'))
            ->filter(fn ($c) => filled($c))
            ->unique()
            ->values();

        return view('pages.projects.index', compact('hero', 'projects', 'categories'));
    }

    public function show(Project $project)
    {
        $hero = HeroStyle::forPage('projects');

        $related = Project::where('id', '!=', $project->id)
            ->when($project->category, fn ($q) => $q->where('category', $project->category))
            ->orderByDesc('is_featured')
            ->orderBy('position')
            ->limit(3)
            ->get();

        return view('pages.projects.show', compact('hero', 'project', 'related'));
    }
}
