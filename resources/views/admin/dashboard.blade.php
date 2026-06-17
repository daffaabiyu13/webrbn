@extends('admin.layout')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
    @php
        $cards = [
            ['Total Produk', $stats['products'], 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10', 'bg-primary'],
            ['Produk Unggulan', $stats['featured'], 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.921-.755 1.688-1.539 1.118l-3.37-2.447a1 1 0 00-1.175 0l-3.37 2.447c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.06 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.289-3.958z', 'bg-accent'],
            ['Kategori', $stats['categories'], 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v2', 'bg-secondary'],
        ];
    @endphp
    @foreach ($cards as [$label, $value, $icon, $color])
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-800">{{ $value }}</p>
                </div>
                <span class="flex h-12 w-12 items-center justify-center rounded-xl text-white {{ $color }}">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $icon }}"/></svg>
                </span>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-8 rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
        <h2 class="text-base font-semibold text-gray-800">Produk Terbaru</h2>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-primary hover:underline">Kelola Produk &rarr;</a>
    </div>
    <ul class="divide-y divide-gray-100">
        @forelse ($recentProducts as $product)
            <li class="flex items-center gap-4 px-6 py-4">
                <img src="{{ $product->imageUrl() }}" alt="" class="h-12 w-16 flex-none rounded-lg object-cover">
                <div class="min-w-0 flex-1">
                    <p class="truncate font-medium text-gray-800">{{ $product->name }}</p>
                    <p class="text-xs text-gray-500">{{ $product->category?->name ?? '—' }}</p>
                </div>
                @if ($product->is_featured)
                    <span class="rounded-full bg-accent/15 px-2.5 py-1 text-xs font-semibold text-accent">Unggulan</span>
                @endif
                <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-semibold text-primary hover:underline">Edit</a>
            </li>
        @empty
            <li class="px-6 py-10 text-center text-gray-500">Belum ada produk.</li>
        @endforelse
    </ul>
</div>

@endsection
