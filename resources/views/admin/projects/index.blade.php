@extends('admin.layout')

@section('title', 'Proyek')
@section('heading', 'Kelola Proyek')

@section('content')

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-1 flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul, klien, lokasi..."
               class="flex-1 min-w-[220px] rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
        <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">Cari</button>
        @if (request('search'))
            <a href="{{ route('admin.projects.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-cream">Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Proyek
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-cream/60 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3">Proyek</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Klien</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3 text-center">Tahun</th>
                    <th class="px-5 py-3 text-center">Featured</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($projects as $project)
                    <tr class="hover:bg-cream/40">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $project->imageUrl() }}" alt="" class="h-12 w-16 rounded-md object-cover bg-cream">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 truncate">{{ $project->title }}</p>
                                    <p class="text-xs text-gray-500 font-mono">/{{ $project->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $project->category ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $project->client ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $project->location ?? '—' }}</td>
                        <td class="px-5 py-3 text-center text-sm text-gray-700">{{ $project->year ?? '—' }}</td>
                        <td class="px-5 py-3 text-center">
                            @if ($project->is_featured)
                                <span class="inline-flex items-center rounded-full bg-secondary/40 px-2.5 py-1 text-xs font-semibold text-primary">
                                    ★ Featured
                                </span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('projects.show', $project) }}" target="_blank"
                                   class="rounded-md bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200">
                                    Lihat
                                </a>
                                <a href="{{ route('admin.projects.edit', $project) }}"
                                   class="rounded-md bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary hover:text-white">
                                    Edit
                                </a>
                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST"
                                      onsubmit="return confirm('Hapus proyek {{ $project->title }}?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-600 hover:text-white">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-gray-500">Belum ada proyek. Klik "Tambah Proyek" untuk mulai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($projects->hasPages())
        <div class="border-t border-gray-100 px-5 py-3">
            {{ $projects->links() }}
        </div>
    @endif
</div>

@endsection
