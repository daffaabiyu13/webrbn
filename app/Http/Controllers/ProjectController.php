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

        $hero = HeroStyle::forPage('projects');

        return view('pages.projects.index', compact('projects', 'hero'));
    }

    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = Project::where('is_published', true)
            ->where('id', '!=', $project->id)
            ->orderBy('position')
            ->orderByDesc('year')
            ->take(3)
            ->get();

        return view('pages.projects.show', compact('project', 'related'));
    }
}
