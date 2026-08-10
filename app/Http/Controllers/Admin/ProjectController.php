<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Support\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with('images')
            ->when($request->input('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderBy('position')
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(ProjectRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title']);
        unset($data['images'], $data['delete_images']);

        $project = Project::create($data);

        $this->syncImages($project, $request);

        return redirect()->route('admin.projects.index')->with('status', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        $project->load('images');

        return view('admin.projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title'], $project->id);
        unset($data['images'], $data['delete_images']);

        $project->update($data);

        $this->syncImages($project, $request);

        return redirect()->route('admin.projects.index')->with('status', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        foreach ($project->images as $image) {
            if (! str_starts_with($image->path, 'http')) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project berhasil dihapus.');
    }

    private function syncImages(Project $project, ProjectRequest $request): void
    {
        $deleteIds = array_filter((array) $request->input('delete_images', []));
        if ($deleteIds) {
            $toDelete = $project->images()->whereIn('id', $deleteIds)->get();
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

        $nextPosition = (int) ($project->images()->max('position') ?? -1) + 1;
        $compressor = ImageCompressor::forProducts();

        foreach ($files as $file) {
            if (! $file) {
                continue;
            }
            $path = $compressor->storeCompressed($file, 'public', 'projects');
            ProjectImage::create([
                'project_id' => $project->id,
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

        while (Project::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base . '-' . (++$i);
        }

        return $candidate;
    }
}
