@extends('layouts.app')

@section('title', 'Solusi Komponen Elektrikal Industrial')
@section('meta_description', 'PT. Radika Bintang Nusantara - Authorized Schneider Electric Partner. General Supplier & System Integrator komponen elektrikal untuk Oil & Gas, Mining, Building, Food & Beverages, dan Water Segment.')

@section('content')

{{-- SECTION 1: HERO --}}
<section class="relative flex min-h-[90vh] items-center overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-dark via-primary to-[#0d2a0b]"></div>
    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 30%, #6DBE45 0, transparent 40%), radial-gradient(circle at 80% 70%, #6DBE45 0, transparent 35%);"></div>

    <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-24 text-center text-white">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium ring-1 ring-white/20 backdrop-blur">
            <svg class="h-4 w-4 text-secondary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.3 3.29 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
            Authorized Schneider Electric Partner
        </span>

        <h1 class="mt-6 text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">PT. Radika Bintang Nusantara</h1>
        <h2 class="mt-3 text-xl font-medium text-secondary sm:text-2xl">General Supplier &amp; System Integrator</h2>
        <p class="mx-auto mt-6 max-w-2xl text-base text-white/80 sm:text-lg">
            Solusi terpadu komponen elektrikal untuk industri Oil &amp; Gas, Mining, Building, Food &amp; Beverages, dan Water Segment.
        </p>

        <div class="mt-9 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-secondary px-7 py-3.5 text-base font-semibold text-white shadow-lg transition-colors hover:bg-[#5aa838]">
                Lihat Katalog Produk
            </a>
            <a href="{{ route('about') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-white/40 px-7 py-3.5 text-base font-semibold text-white transition-colors hover:bg-white hover:text-primary">
                Tentang Perusahaan
            </a>
        </div>
    </div>

    {{-- Hero statistics --}}
    <div class="absolute bottom-0 left-0 right-0 border-t border-white/10 bg-black/20 backdrop-blur">
        <div class="mx-auto grid max-w-5xl grid-cols-2 divide-x divide-white/10 px-4 sm:grid-cols-4">
            @foreach ([['Berdiri', 'Sejak 2025'], ['Kantor', '2 Lokasi'], ['Industri', '5+ Segmen'], ['Partner', 'Schneider']] as $stat)
                <div class="px-2 py-6 text-center text-white">
                    <div class="text-2xl font-extrabold text-secondary">{{ $stat[1] }}</div>
                    <div class="mt-1 text-xs uppercase tracking-wider text-white/70">{{ $stat[0] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 2: BEST PRODUCTS --}}
<section class="bg-offwhite py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">Produk Pilihan</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A] sm:text-4xl">Produk Unggulan Kami</h2>
            <p class="mt-3 text-gray-600">Solusi terbaik dari brand terpercaya dunia</p>
        </div>

        @if ($featuredProducts->isNotEmpty())
            <div class="mt-12 grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3 fade-up">
                @foreach ($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <p class="mt-12 text-center text-gray-500">Belum ada produk unggulan.</p>
        @endif

        <div class="mt-12 text-center fade-up">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-primary-dark">
                Lihat Semua Produk
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- SECTION 3: COMPANY SNAPSHOT --}}
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div class="fade-up">
                <span class="text-sm font-semibold uppercase tracking-wider text-secondary">Tentang Kami</span>
                <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A] sm:text-4xl">Mitra Terpercaya Solusi Elektrikal Industrial</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    Didirikan tahun 2025 dan berlokasi di Sidoarjo, Jawa Timur, PT. Radika Bintang Nusantara adalah General Supplier &amp; System Integrator yang melayani kebutuhan komponen elektrikal industri dengan dukungan teknis dan layanan purna jual yang responsif.
                </p>

                <ul class="mt-6 space-y-3">
                    @foreach ([
                        'Authorized Schneider Electric Partner',
                        'Technical Support & After Sales Service',
                        'System Integrator LV/MV & Automation',
                        'Melayani 5+ Segmen Industri',
                    ] as $point)
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-6 w-6 flex-none items-center justify-center rounded-full bg-secondary/15 text-primary">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.3 3.29 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                            </span>
                            <span class="text-gray-700">{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('about') }}" class="mt-8 inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-primary-dark">
                    Pelajari Lebih Lanjut
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-5 fade-up">
                @foreach ([
                    ['Oil & Gas', 'M3 13l2-7h14l2 7M5 13h14v6a1 1 0 01-1 1H6a1 1 0 01-1-1v-6z'],
                    ['Mining', 'M3 21l6-6m0 0l4-9 8 8-9 4m-3 1l-1-1'],
                    ['Building', 'M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01'],
                    ['Food & Beverages', 'M5 3v18M5 8h6M11 3v18M16 3c2 2 2 6 0 8v10'],
                ] as $industry)
                    <div class="flex flex-col items-center justify-center rounded-2xl bg-offwhite p-8 text-center shadow-sm ring-1 ring-gray-100">
                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $industry[1] }}"/></svg>
                        </span>
                        <span class="mt-4 font-semibold text-gray-800">{{ $industry[0] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- SECTION 4: BRAND PARTNERS --}}
@include('partials.brand-partners')

@endsection
