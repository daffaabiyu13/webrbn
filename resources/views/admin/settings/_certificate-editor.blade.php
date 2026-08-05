{{-- Expects: $certificate = ['image', 'valid_text', 'signed_text'] --}}
@php $limit = \App\Support\UploadLimit::forHeroBackground(); @endphp

<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100"
     x-data="{
        preview: null,
        fileName: '',
        removeImage: false,
        handle(e) {
            const f = e.target.files && e.target.files[0];
            if (!f) { this.preview = null; this.fileName = ''; return; }
            this.fileName = f.name;
            this.removeImage = false;
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
            <template x-if="!preview && !removeImage">
                @if ($certificate['image'])
                    <img src="{{ asset('storage/' . $certificate['image']) }}" alt="Sertifikat saat ini" class="w-full h-auto object-contain max-h-72 mx-auto">
                @else
                    <div class="flex h-56 items-center justify-center text-center text-xs text-gray-400 px-4">
                        Belum ada sertifikat. Pilih file di bawah untuk pratinjau.
                    </div>
                @endif
            </template>
            <template x-if="!preview && removeImage">
                <div class="flex h-56 items-center justify-center text-center text-xs text-red-600 px-4">
                    Gambar sertifikat akan dihapus setelah disimpan.
                </div>
            </template>
        </div>

        <p class="mt-2 text-xs text-gray-500" x-show="preview" x-cloak>
            Pratinjau file baru: <span x-text="fileName" class="font-medium"></span>
            <button type="button" @click="reset()" class="ml-1 text-primary hover:underline">batal</button>
        </p>
    </div>

    <div class="mt-5 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Unggah Sertifikat</label>
            <input type="file" name="certificate[image]" accept="image/*" x-ref="file" @change="handle($event)"
                   class="mt-2 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
            <p class="mt-1 text-xs text-gray-400">JPG/PNG/WebP. Otomatis dikompres. Batas server: <b>{{ $limit->human() }}</b>.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Teks Masa Berlaku</label>
            <input type="text" name="certificate[valid_text]" maxlength="500"
                   value="{{ old('certificate.valid_text', $certificate['valid_text']) }}"
                   placeholder="Contoh: Berlaku 1 Januari 2026 s/d 31 Desember 2026"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
            <p class="mt-1 text-xs text-gray-400">Muncul sebagai badge hijau di kartu sertifikat.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Teks Pengesahan / Signer</label>
            <textarea name="certificate[signed_text]" rows="2" maxlength="1000"
                      placeholder="Contoh: Disahkan oleh Tonny Hendro Kusumo — Industry Business Vice President, PT Schneider Indonesia."
                      class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('certificate.signed_text', $certificate['signed_text']) }}</textarea>
            <p class="mt-1 text-xs text-gray-400">Nama + jabatan + perusahaan yang menandatangani sertifikat.</p>
        </div>

        @if ($certificate['image'])
            <label class="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50/40 p-3 cursor-pointer hover:bg-red-50">
                <input type="hidden" name="certificate[remove_image]" value="0">
                <input type="checkbox" name="certificate[remove_image]" value="1"
                       x-model="removeImage"
                       class="mt-0.5 rounded border-red-300 text-red-600 focus:ring-red-500">
                <span>
                    <span class="block text-sm font-medium text-red-700">Hapus gambar sertifikat saat menyimpan</span>
                    <span class="block text-xs text-red-600/80">Teks masa berlaku &amp; pengesahan tetap tersimpan. Kalau kamu upload gambar baru di atas, checkbox ini otomatis diabaikan.</span>
                </span>
            </label>
        @endif

        <div>
            <a href="{{ route('about') }}" target="_blank" class="inline-block rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-cream">
                Buka About &nearr;
            </a>
        </div>
    </div>
</div>
