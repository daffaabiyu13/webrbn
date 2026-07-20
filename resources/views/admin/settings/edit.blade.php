@extends('admin.layout')

@section('title', 'Settings')
@section('heading', 'Settings Website')

@section('content')

<div class="max-w-3xl space-y-6">
    @foreach ($heroes as $hero)
        @include('admin.settings._hero-editor', ['hero' => $hero])
    @endforeach

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="text-base font-semibold text-gray-800">Tentang Tampilan Hero</h2>
        <p class="mt-2 text-sm text-gray-600">
            Setiap hero akan otomatis diberi overlay gradient hijau gelap agar teks tetap terbaca. Gunakan foto landscape dengan area gelap di tengah/atas untuk hasil terbaik. Kosongkan gambar (Hapus) untuk kembali ke gradient default.
        </p>
    </div>
</div>

@endsection
