@php
    $product = $product ?? null;

    $specsText = '';
    if ($product && is_array($product->specifications)) {
        foreach ($product->specifications as $k => $v) {
            $specsText .= "{$k}: {$v}\n";
        }
        $specsText = trim($specsText);
    }
    $specsText = old('specifications', $specsText);

    $featuresText = '';
    if ($product && is_array($product->features)) {
        $featuresText = implode("\n", $product->features);
    }
    $featuresText = old('features', $featuresText);
@endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Main column --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Informasi Produk</h2>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $product->name ?? '') }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug <span class="text-xs font-normal text-gray-400">(opsional, otomatis dibuat jika kosong)</span></label>
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

                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Singkat <span class="text-red-500">*</span></label>
                    <textarea name="short_description" required rows="3" maxlength="500"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Tampil di kartu produk &amp; meta description.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="full_description" required rows="8"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('full_description', $product->full_description ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">HTML diperbolehkan (mis. <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>).</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Spesifikasi &amp; Fitur</h2>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Spesifikasi</label>
                    <textarea name="specifications" rows="6" placeholder="Voltage Range: 2.4 kV - 13.8 kV&#10;Power Range: Up to 20 MW"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ $specsText }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Satu baris = satu spesifikasi, format <code>Key: Value</code>.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Fitur &amp; Keunggulan</label>
                    <textarea name="features" rows="6" placeholder="Cakupan tegangan 2.4 kV hingga 13.8 kV&#10;Daya hingga 20 MW"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ $featuresText }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Satu fitur per baris.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Side column --}}
    <div class="space-y-5">
        @php
            $currentImage = ($product && $product->image) ? $product->imageUrl() : null;
            $limit = \App\Support\UploadLimit::forProducts();
        @endphp
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
            <h2 class="text-base font-semibold text-gray-800">Gambar Produk</h2>

            {{-- Preview area --}}
            <div class="mt-4 overflow-hidden rounded-lg ring-1 ring-gray-200 bg-cream/40">
                <template x-if="preview">
                    <img :src="preview" alt="Preview" class="w-full h-48 object-cover">
                </template>
                <template x-if="!preview">
                    @if ($currentImage)
                        <img src="{{ $currentImage }}" alt="Gambar saat ini" class="w-full h-48 object-cover">
                    @else
                        <div class="flex h-48 items-center justify-center text-center text-xs text-gray-400 px-4">
                            Belum ada gambar. Pilih file di bawah untuk pratinjau.
                        </div>
                    @endif
                </template>
            </div>

            <p class="mt-2 text-xs text-gray-500" x-show="preview" x-cloak>
                Pratinjau file baru: <span x-text="fileName" class="font-medium"></span>
                <button type="button" @click="reset()" class="ml-1 text-primary hover:underline">batal</button>
            </p>
            @if ($currentImage)
                <p class="mt-2 text-xs text-gray-500" x-show="!preview">Gambar saat ini. Unggah baru untuk mengganti.</p>
            @endif

            <input type="file" name="image" accept="image/*" x-ref="file" @change="handle($event)"
                   class="mt-4 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
            <p class="mt-1 text-xs text-gray-400">JPG/PNG/WebP. Gambar besar akan otomatis di-resize (max 1600px) &amp; dikompres ke JPEG. Batas upload server: <b>{{ $limit->human() }}</b>.</p>
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
