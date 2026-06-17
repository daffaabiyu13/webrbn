@extends('admin.layout')

@section('title', 'Produk')
@section('heading', 'Kelola Produk')

@section('content')

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-1 flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
               class="flex-1 min-w-[180px] rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
        <select name="category" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
            <option value="">Semua kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">Filter</button>
        @if (request('search') || request('category'))
            <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-cream">Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Produk
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-cream/60 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3">Produk</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <tr class="hover:bg-cream/40">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->imageUrl() }}" alt="" class="h-10 w-14 flex-none rounded-md object-cover">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @if ($product->is_featured)
                                <span class="rounded-full bg-accent/15 px-2.5 py-1 text-xs font-semibold text-accent">Unggulan</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">Biasa</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('catalog.show', $product->slug) }}" target="_blank" class="rounded-md p-2 text-gray-500 hover:bg-cream hover:text-primary" title="Lihat publik">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.46 12C3.73 7.94 7.52 5 12 5c4.48 0 8.27 2.94 9.54 7-1.27 4.06-5.06 7-9.54 7-4.48 0-8.27-2.94-9.54-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="rounded-md bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary hover:text-white">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-600 hover:text-white">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-gray-500">Tidak ada produk yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($products->hasPages())
        <div class="border-t border-gray-100 px-5 py-3">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
