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

    {{-- Certificate uploader --}}
    @php $limit = \App\Support\UploadLimit::forHeroBackground(); @endphp
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
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-800">Sertifikat Schneider Partner</h2>
                <p class="mt-1 text-sm text-gray-500">Muncul di halaman <b>About → Sertifikasi &amp; Partnership</b>. Upload gambar sertifikat asli (PNG/JPG).</p>
            </div>
            <span class="rounded-full bg-secondary/15 px-3 py-1 text-xs font-semibold text-primary">Certificate</span>
        </div>

        {{-- Preview --}}
        <div class="mt-5">
            <div class="relative overflow-hidden rounded-xl ring-1 ring-gray-200 bg-cream/40">
                <template x-if="preview">
                    <img :src="preview" alt="Preview" class="w-full h-auto object-contain max-h-72 mx-auto">
                </template>
                <template x-if="!preview">
                    @if ($certificate['image'])
                        <img src="{{ asset('storage/' . $certificate['image']) }}" alt="Sertifikat saat ini" class="w-full h-auto object-contain max-h-72 mx-auto">
                    @else
                        <div class="flex h-56 items-center justify-center text-center text-xs text-gray-400 px-4">
                            Belum ada sertifikat. Pilih file di bawah untuk pratinjau.
                        </div>
                    @endif
                </template>
            </div>

            <p class="mt-2 text-xs text-gray-500" x-show="preview" x-cloak>
                Pratinjau file baru: <span x-text="fileName" class="font-medium"></span>
                <button type="button" @click="reset()" class="ml-1 text-primary hover:underline">batal</button>
            </p>
        </div>

        <form method="POST" action="{{ route('admin.settings.certificate.update') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Unggah Sertifikat</label>
                <input type="file" name="certificate_image" accept="image/*" x-ref="file" @change="handle($event)"
                       class="mt-2 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
                <p class="mt-1 text-xs text-gray-400">JPG/PNG/WebP. Otomatis dikompres. Batas server: <b>{{ $limit->human() }}</b>.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Teks Masa Berlaku</label>
                <input type="text" name="cert_valid_text" maxlength="500"
                       value="{{ old('cert_valid_text', $certificate['valid_text']) }}"
                       placeholder="Contoh: Berlaku 1 Januari 2026 s/d 31 Desember 2026"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                <p class="mt-1 text-xs text-gray-400">Muncul sebagai badge hijau di kartu sertifikat.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Teks Pengesahan / Signer</label>
                <textarea name="cert_signed_text" rows="2" maxlength="1000"
                          placeholder="Contoh: Disahkan oleh Tonny Hendro Kusumo — Industry Business Vice President, PT Schneider Indonesia."
                          class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('cert_signed_text', $certificate['signed_text']) }}</textarea>
                <p class="mt-1 text-xs text-gray-400">Nama + jabatan + perusahaan yang menandatangani sertifikat.</p>
            </div>

            <div class="flex flex-wrap gap-2 pt-1">
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">
                    Simpan
                </button>

                @if ($certificate['image'])
                    <button type="submit" name="remove_certificate" value="1"
                            onclick="return confirm('Hapus gambar sertifikat? (Teks tetap tersimpan)')"
                            class="rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600">
                        Hapus Gambar
                    </button>
                @endif

                <a href="{{ route('about') }}" target="_blank" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-cream">
                    Buka About &nearr;
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
