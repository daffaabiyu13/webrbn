@extends('layouts.app')

@section('title', 'Katalog Produk')
@section('meta_description', 'Katalog produk PT. Radika Bintang Nusantara - komponen elektrikal MV/LV, drive, switchgear, SCADA, dan transformer dari brand terpercaya dunia.')

@section('content')

{{-- Page hero --}}
<section class="relative flex h-[300px] items-center justify-center overflow-hidden">
    @if (!empty($heroBackground))
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $heroBackground }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-primary-dark/85 via-primary/75 to-[#0d2a0b]/85"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-primary-dark via-primary to-[#0d2a0b]"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 30% 40%, #6DBE45 0, transparent 40%);"></div>
    @endif
    <div class="relative text-center text-white">
        <h1 class="text-4xl font-extrabold sm:text-5xl">Katalog Produk</h1>
        <nav class="mt-3 text-sm text-white/80">
            <a href="{{ route('home') }}" class="hover:text-secondary">Home</a>
            <span class="mx-2">/</span>
            <span class="text-secondary">Catalog</span>
        </nav>
    </div>
</section>

<section class="bg-offwhite py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Filter bar --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <form method="GET" action="{{ route('catalog.index') }}" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                {{-- Category pills --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('catalog.index', request('search') ? ['search' => request('search')] : []) }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ !request('category') ? 'bg-primary text-white' : 'bg-cream text-gray-700 hover:bg-secondary/15 hover:text-primary' }}">
                        All
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('catalog.index', array_filter(['category' => $category->id, 'search' => request('search')])) }}"
                           class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ request('category') == $category->id ? 'bg-primary text-white' : 'bg-cream text-gray-700 hover:bg-secondary/15 hover:text-primary' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                {{-- Search --}}
                <div class="relative w-full lg:w-72 flex-none">
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="search" name="search" value="{{ request('search') }}"
                           placeholder="Cari produk..."
                           class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                </div>
            </form>
        </div>

        @if (request('search') || request('category'))
            <div class="mt-4 flex items-center gap-2 text-sm text-gray-600">
                <span>{{ $products->total() }} produk ditemukan</span>
                <a href="{{ route('catalog.index') }}" class="font-semibold text-primary hover:underline">Reset filter</a>
            </div>
        @endif

        {{-- Product grid --}}
        @if ($products->isNotEmpty())
            <div class="mt-8 grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <div class="mt-12 rounded-2xl bg-white p-16 text-center shadow-sm">
                <p class="text-lg font-semibold text-gray-700">Produk tidak ditemukan</p>
                <p class="mt-2 text-gray-500">Coba ubah kata kunci pencarian atau filter kategori.</p>
                <a href="{{ route('catalog.index') }}" class="mt-6 inline-block rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary-dark">Lihat Semua Produk</a>
            </div>
        @endif
    </div>
</section>

@endsection
