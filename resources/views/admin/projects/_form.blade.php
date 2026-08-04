@php
    $project = $project ?? null;
    $isEdit = (bool) $project;
@endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Main --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Informasi Dasar</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Judul (ID) <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $project?->title) }}" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Judul (EN)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $project?->title_en) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan kalau tidak ada versi Inggris.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Slug URL</label>
                    <input type="text" name="slug" value="{{ old('slug', $project?->slug) }}" placeholder="otomatis dari judul"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori (ID)</label>
                    <input type="text" name="category" value="{{ old('category', $project?->category) }}" placeholder="mis. Oil & Gas, Building..."
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori (EN)</label>
                    <input type="text" name="category_en" value="{{ old('category_en', $project?->category_en) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Klien</label>
                    <input type="text" name="client" value="{{ old('client', $project?->client) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $project?->location) }}" placeholder="Kota, Provinsi"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun</label>
                    <input type="number" name="year" min="1990" max="2100" value="{{ old('year', $project?->year) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Urutan Tampil</label>
                    <input type="number" name="position" min="0" value="{{ old('position', $project?->position ?? 0) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    <p class="mt-1 text-xs text-gray-500">Angka kecil tampil lebih dulu.</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Deskripsi</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ringkasan (ID) <span class="text-red-500">*</span></label>
                    <textarea name="summary" rows="2" required maxlength="500"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('summary', $project?->summary) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Tampil di kartu proyek. Maks 500 karakter.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ringkasan (EN)</label>
                    <textarea name="summary_en" rows="2" maxlength="500"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('summary_en', $project?->summary_en) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Lengkap (ID)</label>
                    <textarea name="description" rows="8"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('description', $project?->description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Enter dua kali untuk paragraf baru.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Lengkap (EN)</label>
                    <textarea name="description_en" rows="8"
                              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('description_en', $project?->description_en) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Foto Utama</h2>
            @if ($project?->image)
                <img src="{{ $project->imageUrl() }}" alt="" class="mb-3 aspect-[4/3] w-full rounded-lg object-cover bg-cream">
            @else
                <div class="mb-3 flex aspect-[4/3] w-full items-center justify-center rounded-lg bg-cream text-xs text-gray-400">
                    Belum ada foto
                </div>
            @endif
            <input type="file" name="image" accept="image/*"
                   class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-primary-dark">
            <p class="mt-2 text-xs text-gray-500">JPG/PNG/WEBP. Otomatis di-resize maks 1600px.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Publikasi</h2>
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1"
                       {{ old('is_featured', $project?->is_featured) ? 'checked' : '' }}
                       class="mt-1 h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                <span class="text-sm">
                    <span class="font-medium text-gray-800">Featured Project</span>
                    <span class="block text-xs text-gray-500">Tampil paling atas dengan badge featured.</span>
                </span>
            </label>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.projects.index') }}" class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-cream">
                Batal
            </a>
            <button type="submit" class="flex-1 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
                {{ $isEdit ? 'Simpan' : 'Tambah' }}
            </button>
        </div>
    </div>
</div>
