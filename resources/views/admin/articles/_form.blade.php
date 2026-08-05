@php
    $article = $article ?? null;
    $existingImage = ($article && $article->image) ? asset('storage/' . $article->image) : null;
    $limit = \App\Support\UploadLimit::forProducts();
@endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="space-y-5 lg:col-span-2" x-data="{ lang: 'id' }">
        <div class="flex items-center gap-2 rounded-full bg-cream p-1 w-fit">
            <button type="button" @click="lang = 'id'"
                    :class="lang === 'id' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 hover:text-primary'"
                    class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-xs font-semibold transition-colors">
                🇮🇩 Bahasa Indonesia
            </button>
            <button type="button" @click="lang = 'en'"
                    :class="lang === 'en' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 hover:text-primary'"
                    class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-xs font-semibold transition-colors">
                🇬🇧 English (opsional)
            </button>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Informasi Artikel</h2>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug <span class="text-xs font-normal text-gray-400">(opsional, otomatis dibuat)</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $article->slug ?? '') }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">URL Artikel <span class="text-xs font-normal text-gray-400">(link ke sumber, opsional)</span></label>
                    <input type="url" name="url" value="{{ old('url', $article->url ?? '') }}"
                           placeholder="https://contoh.com/artikel-lengkap"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    <p class="mt-1 text-xs text-gray-400">Tombol "Baca Selengkapnya" akan mengarah ke URL ini (dibuka di tab baru).</p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Client</label>
                        <input type="text" name="client" value="{{ old('client', $article->client ?? '') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" value="{{ old('location', $article->location ?? '') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Project Size</label>
                        <input type="text" name="project_size" value="{{ old('project_size', $article->project_size ?? '') }}"
                               placeholder="Contoh: Trafo 1600 kVA / 20 kV"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Year</label>
                        <input type="number" name="year" min="1900" max="2100" value="{{ old('year', $article->year ?? date('Y')) }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                </div>

                {{-- Indonesia --}}
                <div x-show="lang === 'id'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Artikel (ID) <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title', $article->title ?? '') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Singkat (ID)</label>
                        <textarea name="short_description" rows="3" maxlength="1000"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('short_description', $article->short_description ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Tampil di kartu artikel (max 4 baris).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Lengkap (ID)</label>
                        <textarea name="description" rows="6"
                                  placeholder="Ringkasan artikel atau catatan tambahan.&#10;&#10;Enter dua kali untuk paragraf baru."
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('description', $article->description ?? '') }}</textarea>
                    </div>
                </div>

                {{-- English --}}
                <div x-show="lang === 'en'" x-cloak class="space-y-4">
                    <div class="rounded-lg bg-secondary/10 border border-secondary/30 px-3 py-2 text-xs text-primary">
                        Kolom English opsional. Kalau dikosongkan, versi Indonesia yang ditampilkan saat user pilih English.
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Article Title (EN)</label>
                        <input type="text" name="title_en" value="{{ old('title_en', $article->title_en ?? '') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Short Description (EN)</label>
                        <textarea name="short_description_en" rows="3" maxlength="1000"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('short_description_en', $article->short_description_en ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Description (EN)</label>
                        <textarea name="description_en" rows="6"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('description_en', $article->description_en ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-5">
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
            <h2 class="text-base font-semibold text-gray-800">Gambar Artikel</h2>

            <div class="mt-4 overflow-hidden rounded-lg ring-1 ring-gray-200 bg-cream/40">
                <template x-if="preview">
                    <img :src="preview" alt="Preview" class="w-full h-48 object-cover">
                </template>
                <template x-if="!preview">
                    @if ($existingImage)
                        <img src="{{ $existingImage }}" alt="Gambar saat ini" class="w-full h-48 object-cover">
                    @else
                        <div class="flex h-48 items-center justify-center text-center text-xs text-gray-400 px-4">
                            Belum ada gambar. Pilih file di bawah.
                        </div>
                    @endif
                </template>
            </div>

            <p class="mt-2 text-xs text-gray-500" x-show="preview" x-cloak>
                Pratinjau: <span x-text="fileName" class="font-medium"></span>
                <button type="button" @click="reset()" class="ml-1 text-primary hover:underline">batal</button>
            </p>

            <input type="file" name="image" accept="image/*" x-ref="file" @change="handle($event)"
                   class="mt-4 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
            <p class="mt-1 text-xs text-gray-400">JPG/PNG/WebP. Otomatis di-resize (max 1600px) &amp; dikompres. Batas: <b>{{ $limit->human() }}</b>.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Status &amp; Urutan</h2>

            <div class="mt-4 space-y-4">
                <label class="flex items-start gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-cream/40">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" class="mt-0.5 rounded border-gray-300 text-primary focus:ring-primary"
                           @checked(old('is_published', $article->is_published ?? true))>
                    <span>
                        <span class="block text-sm font-medium text-gray-800">Publikasikan</span>
                        <span class="block text-xs text-gray-500">Hanya artikel yang dipublikasikan muncul di halaman publik.</span>
                    </span>
                </label>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Posisi Urutan</label>
                    <input type="number" name="position" min="0" value="{{ old('position', $article->position ?? 0) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    <p class="mt-1 text-xs text-gray-400">Angka lebih kecil = tampil lebih dulu. Sama saja urutan → berdasarkan tahun.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <button type="submit" class="w-full rounded-lg bg-primary px-5 py-3 text-sm font-semibold text-white hover:bg-primary-dark">
                {{ $article ? 'Simpan Perubahan' : 'Simpan Artikel' }}
            </button>
            <a href="{{ route('admin.articles.index') }}" class="w-full rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-medium text-gray-600 hover:bg-cream">Batal</a>
        </div>
    </div>
</div>
