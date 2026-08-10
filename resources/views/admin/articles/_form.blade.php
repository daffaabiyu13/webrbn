@php
    $article = $article ?? null;
    $existingImages = ($article && $article->relationLoaded('images'))
        ? $article->images
        : ($article ? $article->images()->get() : collect());
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
                    <label class="block text-sm font-medium text-gray-700">URL Sumber <span class="text-xs font-normal text-gray-400">(opsional)</span></label>
                    <input type="url" name="url" value="{{ old('url', $article->url ?? '') }}"
                           placeholder="https://contoh.com/artikel-lengkap"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    <p class="mt-1 text-xs text-gray-400">Kalau diisi, tombol "Baca Selengkapnya" mengarah ke URL ini (dibuka di tab baru).</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun</label>
                    <input type="number" name="year" min="1900" max="2100" value="{{ old('year', $article->year ?? date('Y')) }}"
                           class="mt-1 w-40 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
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
                                  placeholder="Isi artikel lengkap.&#10;&#10;Enter dua kali untuk paragraf baru."
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
                previews: [],
                deleteIds: [],
                handle(e) {
                    this.previews = [];
                    const files = Array.from(e.target.files || []);
                    files.forEach((f) => {
                        const r = new FileReader();
                        r.onload = () => this.previews.push({ src: r.result, name: f.name });
                        r.readAsDataURL(f);
                    });
                },
                clearFiles() {
                    this.previews = [];
                    this.$refs.file.value = '';
                },
                toggleDelete(id) {
                    const idx = this.deleteIds.indexOf(id);
                    if (idx === -1) this.deleteIds.push(id);
                    else this.deleteIds.splice(idx, 1);
                },
                markedForDelete(id) {
                    return this.deleteIds.includes(id);
                }
             }">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Gambar Artikel</h2>
                <span class="text-xs text-gray-400">Multi-image</span>
            </div>
            <p class="mt-1 text-xs text-gray-500">Gambar pertama = cover kartu &amp; hero detail. Sisanya jadi galeri.</p>

            @if ($existingImages->isNotEmpty())
                <div class="mt-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Gambar saat ini</p>
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @foreach ($existingImages as $img)
                            <div class="relative group"
                                 :class="markedForDelete({{ $img->id }}) ? 'opacity-40' : ''">
                                <img src="{{ $img->url() }}" alt="" class="aspect-square w-full rounded-lg object-cover ring-1 ring-gray-200">
                                @if ($loop->first)
                                    <span class="absolute top-1 left-1 rounded bg-primary px-1.5 py-0.5 text-[9px] font-bold uppercase text-white">Cover</span>
                                @endif
                                <label class="absolute inset-0 flex items-center justify-center rounded-lg bg-black/0 hover:bg-black/40 cursor-pointer transition-colors">
                                    <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"
                                           @change="toggleDelete({{ $img->id }})"
                                           class="peer sr-only">
                                    <span class="hidden peer-checked:inline-flex items-center gap-1 rounded-md bg-red-600 px-2 py-1 text-[10px] font-bold text-white">
                                        AKAN DIHAPUS
                                    </span>
                                    <span class="opacity-0 group-hover:opacity-100 peer-checked:hidden inline-flex items-center gap-1 rounded-md bg-white/90 px-2 py-1 text-[10px] font-semibold text-red-600">
                                        Hapus
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="mt-4 text-xs text-gray-500">Belum ada gambar. Pilih file di bawah untuk pratinjau.</p>
            @endif

            <div class="mt-5" x-show="previews.length > 0" x-cloak>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-secondary">Akan diupload</p>
                    <button type="button" @click="clearFiles()" class="text-xs font-semibold text-primary hover:underline">Bersihkan pilihan</button>
                </div>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    <template x-for="(p, i) in previews" :key="i">
                        <div class="relative">
                            <img :src="p.src" :alt="p.name" class="aspect-square w-full rounded-lg object-cover ring-2 ring-secondary">
                            <span class="absolute bottom-1 left-1 right-1 truncate rounded bg-black/70 px-1.5 py-0.5 text-[9px] text-white" x-text="p.name"></span>
                        </div>
                    </template>
                </div>
            </div>

            <input type="file" name="images[]" accept="image/*" multiple x-ref="file" @change="handle($event)"
                   class="mt-4 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
            <p class="mt-1 text-xs text-gray-400">Pilih beberapa file sekaligus. JPG/PNG/WebP, otomatis di-resize (max 1600px) &amp; dikompres. Batas per file: <b>{{ $limit->human() }}</b>.</p>
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
