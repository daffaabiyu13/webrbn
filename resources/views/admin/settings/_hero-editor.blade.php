{{-- Expects: $hero = ['key','label','value','overlay_color','overlay_opacity'] --}}
@php
    $limit = \App\Support\UploadLimit::forHeroBackground();
    $publicUrls = [
        'home' => route('home'),
        'catalog' => route('catalog.index'),
        'projects' => route('projects.index'),
        'articles' => route('articles.index'),
        'about' => route('about'),
    ];
    $viewUrl = $publicUrls[$hero['key']] ?? route('home');
@endphp

<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100"
     x-data="{
        preview: null,
        fileName: '',
        overlayColor: '{{ $hero['overlay_color'] }}',
        overlayOpacity: {{ $hero['overlay_opacity'] }},
        removeImage: false,
        handle(e) {
            const f = e.target.files && e.target.files[0];
            if (!f) { this.preview = null; this.fileName = ''; return; }
            this.fileName = f.name;
            this.removeImage = false; // uploading a new file cancels the remove intent
            const r = new FileReader();
            r.onload = () => this.preview = r.result;
            r.readAsDataURL(f);
        },
        reset() {
            this.preview = null;
            this.fileName = '';
            this.$refs.file.value = '';
        },
        get overlayRgba() {
            const h = this.overlayColor || '#416423';
            const r = parseInt(h.slice(1,3), 16);
            const g = parseInt(h.slice(3,5), 16);
            const b = parseInt(h.slice(5,7), 16);
            return `rgba(${r}, ${g}, ${b}, ${this.overlayOpacity/100})`;
        }
     }">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-800">Hero Background — {{ $hero['label'] }}</h2>
            <p class="mt-1 text-sm text-gray-500">Muncul di belakang teks hero pada halaman <b>/{{ $hero['key'] === 'home' ? '' : $hero['key'] }}</b>.</p>
        </div>
        <span class="rounded-full bg-secondary/15 px-3 py-1 text-xs font-semibold text-primary">{{ ucfirst($hero['key']) }}</span>
    </div>

    {{-- Preview area with dynamic overlay --}}
    <div class="mt-5">
        <div class="relative overflow-hidden rounded-xl ring-1 ring-gray-200 h-56">
            <template x-if="preview">
                <img :src="preview" alt="Preview" class="absolute inset-0 h-full w-full object-cover">
            </template>
            <template x-if="!preview && !removeImage">
                @if ($hero['value'])
                    <img src="{{ asset('storage/' . $hero['value']) }}" alt="Hero background" class="absolute inset-0 h-full w-full object-cover">
                @else
                    <div class="absolute inset-0 bg-gray-200"></div>
                @endif
            </template>
            <template x-if="!preview && removeImage">
                <div class="absolute inset-0 bg-gray-200"></div>
            </template>

            <div class="absolute inset-0" :style="`background-color: ${overlayRgba}`"></div>
            <div class="relative flex h-full items-center justify-center text-center text-white px-6">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-secondary/90">Pratinjau hero {{ strtolower($hero['label']) }}</p>
                    <p class="mt-1 text-xl font-extrabold">PT. Radika Bintang Nusantara</p>
                </div>
            </div>
        </div>

        <p class="mt-2 text-xs text-gray-500" x-show="preview" x-cloak>
            Pratinjau file baru: <span x-text="fileName" class="font-medium"></span>
            <button type="button" @click="reset()" class="ml-1 text-primary hover:underline">batal</button>
        </p>
        <p class="mt-2 text-xs text-red-600" x-show="removeImage && !preview" x-cloak>
            Gambar akan dihapus saat kamu klik Simpan Semua Perubahan.
        </p>
    </div>

    <div class="mt-5 space-y-4">
        {{-- Image --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Gambar Background</label>
            <input type="file" name="heroes[{{ $hero['key'] }}][image]" accept="image/*" x-ref="file" @change="handle($event)"
                   class="mt-2 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
            <p class="mt-1 text-xs text-gray-400">JPG/PNG/WebP. Otomatis di-resize (max 2400x1600) &amp; dikompres. Batas: <b>{{ $limit->human() }}</b>.</p>
        </div>

        {{-- Overlay color + opacity --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Warna Overlay</label>
                <div class="mt-2 flex items-center gap-3">
                    <input type="color" name="heroes[{{ $hero['key'] }}][overlay_color]" x-model="overlayColor"
                           class="h-10 w-14 flex-none cursor-pointer rounded-lg border border-gray-300 bg-white p-1">
                    <input type="text" x-model="overlayColor" pattern="^#[0-9a-fA-F]{6}$" maxlength="7"
                           class="flex-1 rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm uppercase focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <p class="mt-1 text-xs text-gray-400">Warna yang menutupi gambar (default hijau brand).</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Opacity — <span x-text="overlayOpacity + '%'" class="font-mono font-semibold text-primary"></span></label>
                <div class="mt-2 flex items-center gap-3">
                    <input type="range" name="heroes[{{ $hero['key'] }}][overlay_opacity]" min="0" max="100" step="1" x-model.number="overlayOpacity"
                           class="flex-1 accent-primary">
                    <input type="number" min="0" max="100" x-model.number="overlayOpacity"
                           class="w-16 rounded-lg border border-gray-300 px-2 py-2 text-center text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <p class="mt-1 text-xs text-gray-400">0% = gambar terlihat penuh, 100% = warna solid menutup semua.</p>
            </div>
        </div>

        {{-- Remove-image toggle (only if there's an existing image) --}}
        @if ($hero['value'])
            <label class="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50/40 p-3 cursor-pointer hover:bg-red-50">
                <input type="hidden" name="heroes[{{ $hero['key'] }}][remove_image]" value="0">
                <input type="checkbox" name="heroes[{{ $hero['key'] }}][remove_image]" value="1"
                       x-model="removeImage"
                       class="mt-0.5 rounded border-red-300 text-red-600 focus:ring-red-500">
                <span>
                    <span class="block text-sm font-medium text-red-700">Hapus gambar saat menyimpan</span>
                    <span class="block text-xs text-red-600/80">Overlay warna &amp; opacity tetap tersimpan. Kalau kamu upload gambar baru di atas, checkbox ini otomatis diabaikan.</span>
                </span>
            </label>
        @endif

        <div>
            <a href="{{ $viewUrl }}" target="_blank" class="inline-block rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-cream">
                Buka {{ $hero['label'] }} &nearr;
            </a>
        </div>
    </div>
</div>
