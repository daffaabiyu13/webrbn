@extends('admin.layout')

@section('title', 'Kategori Produk')
@section('heading', 'Kelola Kategori')

@section('content')

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-1 flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari kategori..."
               class="flex-1 min-w-[180px] rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
        <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">Cari</button>
        @if (request('search'))
            <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-cream">Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Kategori
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-cream/60 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Slug</th>
                    <th class="px-5 py-3 text-center">Jumlah Produk</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($categories as $category)
                    <tr class="hover:bg-cream/40">
                        <td class="px-5 py-3">
                            <div>
                                <p class="font-medium text-gray-800">{{ $category->name }}</p>
                                @if ($category->name_en)
                                    <p class="text-xs text-gray-500">EN: {{ $category->name_en }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm font-mono text-gray-600">{{ $category->slug }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                                {{ $category->products_count }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-md bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary hover:text-white">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-600 hover:text-white
                                                   @if ($category->products_count > 0) opacity-60 @endif"
                                            @if ($category->products_count > 0) title="Kategori masih punya produk" @endif>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-gray-500">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($categories->hasPages())
        <div class="border-t border-gray-100 px-5 py-3">
            {{ $categories->links() }}
        </div>
    @endif
</div>

@endsection
