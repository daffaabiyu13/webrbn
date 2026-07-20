@extends('layouts.app')

@section('title', 'Tentang Kami')
@section('meta_description', 'Tentang PT. Radika Bintang Nusantara - Authorized Supplier & System Integrator komponen elektrikal. Didirikan 2025 di Sidoarjo, Jawa Timur.')

@section('content')

{{-- SECTION 1: HERO --}}
<section class="relative flex min-h-[340px] items-center overflow-hidden">
    @if (!empty($hero['background']))
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
    @else
        <div class="absolute inset-0 bg-gray-900"></div>
    @endif
    <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>
    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-20 text-center text-white">
        <h1 class="text-4xl font-extrabold sm:text-5xl">Tentang PT. Radika Bintang Nusantara</h1>
        <p class="mt-4 text-lg text-secondary">Authorized Supplier &amp; System Integrator Komponen Elektrikal</p>
    </div>
</section>

{{-- SECTION 2: PROFIL PERUSAHAAN --}}
<section class="bg-white py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center fade-up">
        <span class="text-sm font-semibold uppercase tracking-wider text-secondary">Profil Perusahaan</span>
        <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">About Company</h2>
        <p class="mt-6 text-gray-600 leading-relaxed">
            Didirikan tahun 2025 dan berlokasi di Sidoarjo, Jawa Timur, PT. Radika Bintang Nusantara adalah General Supplier &amp; System Integrator yang bergerak di bidang Industrial Equipment untuk Oil &amp; Gas, Mining, Building, Food &amp; Beverages, dan Water Segment.
        </p>
        <p class="mt-4 text-gray-600 leading-relaxed">
            Untuk mengoptimalkan kepuasan pelanggan, tim engineer dan tenaga ahli kami siap menjawab keraguan teknis dan pertanyaan klien, dipadukan dengan tim marketing dan sales yang termotivasi dan responsif. PT. Radika Bintang Nusantara dipercaya oleh perusahaan dan principal luar negeri sebagai mitra produk resmi untuk mendampingi pengguna dalam hal technical consultant, product support, instalasi, aplikasi, commissioning, kalibrasi, dan layanan purna jual.
        </p>
    </div>
</section>

{{-- SECTION 3: VISI & MISI --}}
<section class="bg-offwhite py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-2xl bg-white p-8 shadow-md ring-1 ring-gray-100 fade-up">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.46 12C3.73 7.94 7.52 5 12 5c4.48 0 8.27 2.94 9.54 7-1.27 4.06-5.06 7-9.54 7-4.48 0-8.27-2.94-9.54-7z"/></svg>
                </span>
                <h3 class="mt-5 text-2xl font-bold text-primary">Visi</h3>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    Menjadi supplier spesialis terbaik di bidang elektrikal, energy management, industrial software, dan automation. Selalu berinovasi dengan semangat keunggulan untuk menjadi perusahaan yang lebih baik, menyediakan produk dan solusi unggul yang dipadukan dengan tenaga teknis terampil.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-8 shadow-md ring-1 ring-gray-100 fade-up">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-secondary text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
                <h3 class="mt-5 text-2xl font-bold text-primary">Misi</h3>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    Memberikan solusi berkualitas terbaik, layanan engineering, dan maintenance untuk pelanggan setia kami. Menjadi general supplier elektrikal &amp; system integrator LV/MV dan automation dengan berbagai merek produk dan solusi.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 4: NILAI PERUSAHAAN --}}
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">Nilai Perusahaan</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">Komitmen Kami</h2>
        </div>
        <div class="mt-12 grid grid-cols-1 gap-7 md:grid-cols-3">
            @foreach ([
                ['Market Driven Basic Implementation', 'Menjunjung semangat inovasi dan kreativitas dalam membentuk solusi yang dapat diterima oleh komunitas yang lebih luas.'],
                ['SEM Implementation', 'Memberikan penjelasan bahwa solusi yang ditawarkan berdasarkan data dan analisis yang sangat presisi serta fokus pada permasalahan.'],
                ['Decreased Industry GAP', 'Solusi yang ditawarkan didasarkan pada keputusan pasar yang matang sehingga dapat memberikan dampak nyata.'],
            ] as $i => $value)
                <div class="rounded-2xl bg-offwhite p-7 ring-1 ring-gray-100 fade-up">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-primary text-lg font-bold text-white">{{ $i + 1 }}</span>
                    <h3 class="mt-5 text-lg font-bold text-[#1A1A1A]">{{ $value[0] }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ $value[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 5: DATA PERUSAHAAN --}}
<section class="bg-offwhite py-20">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">Data Perusahaan</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">Informasi Legal &amp; Kontak</h2>
        </div>
        <div class="mt-10 overflow-hidden rounded-2xl bg-white shadow-md ring-1 ring-gray-100 fade-up">
            <dl class="divide-y divide-gray-100">
                @foreach ([
                    ['Kantor Utama', 'Taman Puspa Anggaswangi Blok P1-12 Sukodono, Sidoarjo, Jawa Timur 61258'],
                    ['Perwakilan Kalimantan', 'Jl. Makmur, Graha Permata Indah No.59, Banjarbaru, Kalimantan Selatan 70721'],
                    ['Aspek Legal', 'Nurul Latifah, SH., M.Kn'],
                    ['NIB', '1003250060023'],
                    ['NPWP', '1000 0000 0073 4477'],
                    ['Email', 'pt.radikabintang@gmail.com'],
                    ['Telepon', '081232048578'],
                ] as $row)
                    <div class="grid grid-cols-1 gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-700">{{ $row[0] }}</dt>
                        <dd class="text-sm text-gray-600 sm:col-span-2">{{ $row[1] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</section>

{{-- SECTION 6: SEGMEN INDUSTRI --}}
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">Segmen Industri</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">Industri yang Kami Layani</h2>
        </div>
        <div class="mt-12 grid grid-cols-2 gap-5 md:grid-cols-5 fade-up">
            @foreach ([
                ['Oil & Gas', 'M3 13l2-7h14l2 7M5 13h14v6a1 1 0 01-1 1H6a1 1 0 01-1-1v-6z'],
                ['Mining', 'M3 21l6-6m0 0l4-9 8 8-9 4m-3 1l-1-1'],
                ['Building', 'M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01'],
                ['Food & Beverages', 'M5 3v18M5 8h6M11 3v18M16 3c2 2 2 6 0 8v10'],
                ['Water Segment', 'M12 3l5.66 5.66a8 8 0 11-11.32 0L12 3z'],
            ] as $industry)
                <div class="flex flex-col items-center justify-center rounded-2xl bg-offwhite p-6 text-center ring-1 ring-gray-100">
                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $industry[1] }}"/></svg>
                    </span>
                    <span class="mt-4 text-sm font-semibold text-gray-800">{{ $industry[0] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 7: SERTIFIKASI & PARTNERSHIP --}}
<section class="bg-offwhite py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">Sertifikasi &amp; Partnership</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">Authorized Partner Schneider Electric</h2>
        </div>
        <div class="mt-10 overflow-hidden rounded-2xl bg-white shadow-md ring-1 ring-gray-100 fade-up">
            <div class="bg-primary px-8 py-6 text-white">
                <p class="text-sm uppercase tracking-wider text-secondary">Schneider Electric</p>
                <h3 class="mt-1 text-2xl font-bold">Authorized Partner</h3>
            </div>
            <div class="p-8">
                <p class="text-lg font-semibold text-gray-800">PT. Radika Bintang Nusantara</p>
                <p class="mt-1 text-gray-600">Control Panel Builder Partner</p>
                <p class="mt-4 inline-flex items-center gap-2 rounded-lg bg-secondary/15 px-4 py-2 text-sm font-semibold text-primary">
                    Berlaku 1 Januari 2026 s/d 31 Desember 2026
                </p>
                <p class="mt-5 text-sm text-gray-500">
                    Disahkan oleh Tonny Hendro Kusumo &mdash; Industry Business Vice President, PT Schneider Indonesia.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Brand partners --}}
@include('partials.brand-partners')

@endsection
