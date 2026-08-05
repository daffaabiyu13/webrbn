@extends('admin.layout')

@section('title', 'Settings')
@section('heading', 'Settings Website')

@section('content')

<form method="POST" action="{{ route('admin.settings.save') }}" enctype="multipart/form-data" class="max-w-3xl space-y-6 pb-24">
    @csrf

    @foreach ($heroes as $hero)
        @include('admin.settings._hero-editor', ['hero' => $hero])
    @endforeach

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="text-base font-semibold text-gray-800">Tentang Tampilan Hero</h2>
        <p class="mt-2 text-sm text-gray-600">
            Setiap hero akan otomatis diberi overlay warna agar teks tetap terbaca. Gunakan foto landscape dengan area gelap di tengah/atas untuk hasil terbaik. Centang <b>Hapus gambar saat menyimpan</b> untuk kembali ke background solid.
        </p>
    </div>

    @include('admin.settings._certificate-editor', ['certificate' => $certificate])

    {{-- Sticky action bar — always in reach, no matter how far the admin scrolled. --}}
    <div class="sticky bottom-4 z-20 mt-8">
        <div class="rounded-2xl bg-white/95 backdrop-blur p-4 shadow-2xl ring-1 ring-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-gray-800">Simpan semua perubahan sekaligus</p>
                <p class="text-xs text-gray-500">Semua hero &amp; sertifikat disimpan dalam satu klik.</p>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary-dark">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Semua Perubahan
            </button>
        </div>
    </div>
</form>

@endsection
