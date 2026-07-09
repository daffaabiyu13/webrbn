@extends('admin.layout')

@section('title', 'Settings')
@section('heading', 'Settings Website')

@section('content')

@php $limit = \App\Support\UploadLimit::forHeroBackground(); @endphp

<div class="max-w-3xl">
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100"
         x-data="{
            preview: null,
            fileName: '',
            handle(e) {
                const f = e.target.files && e.target.files[0];
                if (!f) { this.preview = null; this.fileName = ''; return; }
                this.fileName = f.name;
                const r = new FileReader();
                r.onload = () => this.preview = r.result;
                r.readAsDataURL(f);
            },
            reset() {
                this.preview = null;
                this.fileName = '';
                this.$refs.file.value = '';
            }
         }">
        <h2 class="text-base font-semibold text-gray-800">Gambar Background Hero</h2>
        <p class="mt-1 text-sm text-gray-500">Gambar ini muncul di belakang teks hero pada halaman Home. Kosongkan untuk kembali ke gradient default.</p>

        {{-- Preview area with overlay --}}
        <div class="mt-6">
            <div class="relative overflow-hidden rounded-xl ring-1 ring-gray-200 h-64">
                <template x-if="preview">
                    <img :src="preview" alt="Preview" class="absolute inset-0 h-full w-full object-cover">
                </template>
                <template x-if="!preview">
                    @if ($heroBackground)
                        <img src="{{ asset('storage/' . $heroBackground) }}" alt="Hero background" class="absolute inset-0 h-full w-full object-cover">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-dark via-primary to-[#0d2a0b]"></div>
                    @endif
                </template>

                {{-- Overlay + sample text so you can see how it will look on Home --}}
                <div class="absolute inset-0 bg-gradient-to-br from-primary-dark/85 via-primary/75 to-[#0d2a0b]/85"></div>
                <div class="relative flex h-full items-center justify-center text-center text-white px-6">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-secondary/90">Pratinjau Hero</p>
                        <p class="mt-1 text-2xl font-extrabold">PT. Radika Bintang Nusantara</p>
                        <p class="mt-1 text-sm text-white/80">Solusi terpadu komponen elektrikal</p>
                    </div>
                </div>
            </div>

            <p class="mt-2 text-xs text-gray-500" x-show="preview" x-cloak>
                Pratinjau file baru: <span x-text="fileName" class="font-medium"></span>
                <button type="button" @click="reset()" class="ml-1 text-primary hover:underline">batal</button>
            </p>
            @if (! $heroBackground)
                <p class="mt-2 text-xs text-gray-500" x-show="!preview">Default gradient (belum ada gambar).</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Unggah Gambar Baru</label>
                <input type="file" name="hero_background" accept="image/*" x-ref="file" @change="handle($event)"
                       class="mt-2 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
                <p class="mt-1 text-xs text-gray-400">JPG/PNG/WebP. Gambar besar akan otomatis di-resize (max 2400x1600) &amp; dikompres ke JPEG. Batas upload server: <b>{{ $limit->human() }}</b> (upload_max_filesize={{ $limit->phpUpload() }}, post_max_size={{ $limit->phpPost() }}).</p>
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
                    Buka Home &nearr;
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
