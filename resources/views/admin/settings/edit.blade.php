@extends('admin.layout')

@section('title', 'Settings')
@section('heading', 'Settings Website')

@section('content')

<div class="max-w-3xl">
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="text-base font-semibold text-gray-800">Gambar Background Hero</h2>
        <p class="mt-1 text-sm text-gray-500">Gambar ini muncul di belakang teks hero pada halaman Home. Kosongkan untuk kembali ke gradient default.</p>

        <div class="mt-6">
            <div class="overflow-hidden rounded-xl ring-1 ring-gray-200">
                @if ($heroBackground)
                    <img src="{{ asset('storage/' . $heroBackground) }}" alt="Hero background" class="h-64 w-full object-cover">
                @else
                    <div class="flex h-64 w-full items-center justify-center bg-gradient-to-br from-primary-dark via-primary to-[#0d2a0b] text-white">
                        <span class="text-sm font-medium">Default gradient (belum ada gambar)</span>
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Unggah Gambar Baru</label>
                <input type="file" name="hero_background" accept="image/*" class="mt-2 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
                <p class="mt-1 text-xs text-gray-400">JPG/PNG/WebP, maksimal 6MB. Rekomendasi resolusi minimal 1920x1080.</p>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
                    Simpan
                </button>

                @if ($heroBackground)
                    <button type="submit" name="remove_hero_background" value="1"
                            onclick="return confirm('Hapus gambar hero background?')"
                            class="rounded-lg border border-red-300 px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600">
                        Hapus Gambar
                    </button>
                @endif

                <a href="{{ route('home') }}" target="_blank" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-cream">
                    Pratinjau Home &nearr;
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="text-base font-semibold text-gray-800">Tentang Tampilan Hero</h2>
        <p class="mt-2 text-sm text-gray-600">
            Gambar akan diberi overlay gradient hijau gelap secara otomatis agar teks tetap terbaca. Gunakan foto landscape dengan area gelap di tengah/atas untuk hasil terbaik.
        </p>
    </div>
</div>

@endsection
