@extends('admin.layout')

@section('title', 'Artikel')
@section('heading', 'Kelola Artikel')

@section('content')

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="{{ route('admin.articles.index') }}" class="flex flex-1 flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari artikel..."
               class="flex-1 min-w-[180px] rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
        <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">Cari</button>
        @if (request('search'))
            <a href="{{ route('admin.articles.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-cream">Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Artikel
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-cream/60 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3">Artikel</th>
                    <th class="px-5 py-3">URL</th>
                    <th class="px-5 py-3 text-center">Tahun</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($articles as $article)
                    <tr class="hover:bg-cream/40">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $article->imageUrl() }}" alt="" class="h-10 w-14 flex-none rounded-md object-cover ring-1 ring-gray-200">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 truncate">{{ $article->title }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $article->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm">
                            @if ($article->url)
                                <a href="{{ $article->url }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1 text-primary hover:underline max-w-[240px] truncate">
                                    <svg class="h-3.5 w-3.5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    <span class="truncate">{{ $article->url }}</span>
                                </a>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center text-sm text-gray-600">{{ $article->year ?? '—' }}</td>
                        <td class="px-5 py-3 text-center">
                            @if ($article->is_published)
                                <span class="rounded-full bg-secondary/15 px-2.5 py-1 text-xs font-semibold text-primary">Publish</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="rounded-md bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary hover:text-white">Edit</a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel {{ $article->title }}?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-600 hover:text-white">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-gray-500">Belum ada artikel. Klik "Tambah Artikel" untuk mulai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($articles->hasPages())
        <div class="border-t border-gray-100 px-5 py-3">
            {{ $articles->links() }}
        </div>
    @endif
</div>

@endsection
