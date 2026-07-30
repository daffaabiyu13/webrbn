@php
    $product = $product ?? null;

    $specsToText = function ($val) {
        if (!is_array($val)) return '';
        $t = '';
        foreach ($val as $k => $v) $t .= "{$k}: {$v}\n";
        return trim($t);
    };

    $specsText   = old('specifications', $specsToText($product?->specifications ?? []));
    $specsTextEn = old('specifications_en', $specsToText($product?->specifications_en ?? []));

    $featsToText = fn ($val) => is_array($val) ? implode("\n", $val) : '';
    $featuresText   = old('features', $featsToText($product?->features ?? []));
    $featuresTextEn = old('features_en', $featsToText($product?->features_en ?? []));
@endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Main column --}}
    <div class="space-y-5 lg:col-span-2" x-data="{ lang: 'id' }">

        {{-- Language tabs --}}
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
            <h2 class="text-base font-semibold text-gray-800">Informasi Produk</h2>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug <span class="text-xs font-normal text-gray-400">(opsional, otomatis dibuat)</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug ?? '') }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                    <select name="product_category_id" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="">— pilih kategori —</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('product_category_id', $product->product_category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Indonesia --}}
                <div x-show="lang === 'id'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Produk (ID) <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $product->name ?? '') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Singkat (ID) <span class="text-red-500">*</span></label>
                        <textarea name="short_description" required rows="3" maxlength="500"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Tampil di kartu produk &amp; meta description.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Lengkap (ID) <span class="text-red-500">*</span></label>
                        <textarea name="full_description" required rows="8"
                                  placeholder="Tulis deskripsi lengkap produk di sini.&#10;&#10;Tekan Enter dua kali untuk pindah paragraf baru."
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('full_description', $product->full_description ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Cukup ketik biasa. <b>Tekan Enter</b> untuk baris baru, <b>Enter dua kali</b> untuk paragraf baru — sistem akan format otomatis.</p>
                    </div>
                </div>

                {{-- English --}}
                <div x-show="lang === 'en'" x-cloak class="space-y-4">
                    <div class="rounded-lg bg-secondary/10 border border-secondary/30 px-3 py-2 text-xs text-primary">
                        Kolom English bersifat opsional. Kalau dikosongkan, versi Indonesia yang ditampilkan saat user pilih bahasa English.
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Name (EN)</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $product->name_en ?? '') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Short Description (EN)</label>
                        <textarea name="short_description_en" rows="3" maxlength="500"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('short_description_en', $product->short_description_en ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Description (EN)</label>
                        <textarea name="full_description_en" rows="8"
                                  placeholder="Write the full product description here.&#10;&#10;Press Enter twice to start a new paragraph."
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('full_description_en', $product->full_description_en ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Just type normally. <b>Enter</b> for a line break, <b>Enter twice</b> for a new paragraph.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Spesifikasi &amp; Fitur</h2>

            <div class="mt-5 space-y-4">
                <div x-show="lang === 'id'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Spesifikasi (ID)</label>
                        <textarea name="specifications" rows="6" placeholder="Voltage Range: 2.4 kV - 13.8 kV&#10;Power Range: Up to 20 MW"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ $specsText }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Satu baris = <code>Key: Value</code>.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fitur &amp; Keunggulan (ID)</label>
                        <textarea name="features" rows="6" placeholder="Cakupan tegangan 2.4 kV hingga 13.8 kV&#10;Daya hingga 20 MW"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ $featuresText }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Satu fitur per baris.</p>
                    </div>
                </div>

                <div x-show="lang === 'en'" x-cloak class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Specifications (EN)</label>
                        <textarea name="specifications_en" rows="6"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ $specsTextEn }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">One line per <code>Key: Value</code>.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Features &amp; Highlights (EN)</label>
                        <textarea name="features_en" rows="6"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ $featuresTextEn }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">One feature per line.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Side column --}}
    <div class="space-y-5">
        @php
            $existingImages = ($product && $product->relationLoaded('images')) ? $product->images : ($product ? $product->images()->get() : collect());
            $limit = \App\Support\UploadLimit::forProducts();
        @endphp
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
                <h2 class="text-base font-semibold text-gray-800">Gambar Produk</h2>
                <span class="text-xs text-gray-400">Multi-image</span>
            </div>
            <p class="mt-1 text-xs text-gray-500">Gambar pertama = cover. Sisanya jadi galeri di halaman detail.</p>

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
            <h2 class="text-base font-semibold text-gray-800">Status</h2>
            <label class="mt-4 flex items-start gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-cream/40">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" class="mt-0.5 rounded border-gray-300 text-primary focus:ring-primary"
                       @checked(old('is_featured', $product->is_featured ?? false))>
                <span>
                    <span class="block text-sm font-medium text-gray-800">Tampilkan sebagai produk unggulan</span>
                    <span class="block text-xs text-gray-500">Akan muncul di section "Produk Unggulan Kami" pada home.</span>
                </span>
            </label>
        </div>

        <div class="flex flex-col gap-2">
            <button type="submit" class="w-full rounded-lg bg-primary px-5 py-3 text-sm font-semibold text-white hover:bg-primary-dark">
                {{ $product ? 'Simpan Perubahan' : 'Simpan Produk' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="w-full rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-medium text-gray-600 hover:bg-cream">Batal</a>
        </div>
    </div>
</div>
