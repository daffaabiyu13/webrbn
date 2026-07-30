@php
    $category = $category ?? null;
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
            <h2 class="text-base font-semibold text-gray-800">Informasi Kategori</h2>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug <span class="text-xs font-normal text-gray-400">(opsional, otomatis dibuat)</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Icon <span class="text-xs font-normal text-gray-400">(opsional, mis. bolt, cpu, gauge)</span></label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon ?? '') }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                {{-- Indonesia --}}
                <div x-show="lang === 'id'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Kategori (ID) <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $category->name ?? '') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi (ID)</label>
                        <textarea name="description" rows="4"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('description', $category->description ?? '') }}</textarea>
                    </div>
                </div>

                {{-- English --}}
                <div x-show="lang === 'en'" x-cloak class="space-y-4">
                    <div class="rounded-lg bg-secondary/10 border border-secondary/30 px-3 py-2 text-xs text-primary">
                        Kolom English opsional. Kalau dikosongkan, versi Indonesia yang ditampilkan saat user pilih bahasa English.
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category Name (EN)</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $category->name_en ?? '') }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="description_en" rows="4"
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('description_en', $category->description_en ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Catatan</h2>
            <ul class="mt-3 space-y-2 text-xs text-gray-500">
                <li>&bull; Nama Indonesia wajib diisi.</li>
                <li>&bull; Slug tidak boleh sama dengan kategori lain.</li>
                <li>&bull; Kategori yang punya produk tidak bisa dihapus — pindahkan produk-nya ke kategori lain dulu.</li>
            </ul>
        </div>

        @if ($category)
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Statistik</h2>
                <p class="mt-3 text-sm text-gray-600">
                    Jumlah produk: <span class="font-bold text-primary">{{ $category->products()->count() }}</span>
                </p>
            </div>
        @endif

        <div class="flex flex-col gap-2">
            <button type="submit" class="w-full rounded-lg bg-primary px-5 py-3 text-sm font-semibold text-white hover:bg-primary-dark">
                {{ $category ? 'Simpan Perubahan' : 'Simpan Kategori' }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="w-full rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-medium text-gray-600 hover:bg-cream">Batal</a>
        </div>
    </div>
</div>
