{{-- Expects: $hero = ['key','label','value','overlay_color','overlay_opacity'] --}}
@php $limit = \App\Support\UploadLimit::forHeroBackground(); @endphp

<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100"
     x-data="{
        preview: null,
        fileName: '',
        overlayColor: '{{ $hero['overlay_color'] }}',
        overlayOpacity: {{ $hero['overlay_opacity'] }},
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
            <template x-if="!preview">
                @if ($hero['value'])
                    <img src="{{ asset('storage/' . $hero['value']) }}" alt="Hero background" class="absolute inset-0 h-full w-full object-cover">
                @else
                    <div class="absolute inset-0 bg-gray-200"></div>
                @endif
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
    </div>

    <form method="POST" action="{{ route('admin.settings.hero.update', ['page' => $hero['key']]) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
        @csrf

        {{-- Image --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Gambar Background</label>
            <input type="file" name="hero_background" accept="image/*" x-ref="file" @change="handle($event)"
                   class="mt-2 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
            <p class="mt-1 text-xs text-gray-400">JPG/PNG/WebP. Otomatis di-resize (max 2400x1600) &amp; dikompres. Batas: <b>{{ $limit->human() }}</b>.</p>
        </div>

        {{-- Overlay color + opacity --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Warna Overlay</label>
                <div class="mt-2 flex items-center gap-3">
                    <input type="color" name="overlay_color" x-model="overlayColor"
                           class="h-10 w-14 flex-none cursor-pointer rounded-lg border border-gray-300 bg-white p-1">
                    <input type="text" x-model="overlayColor" pattern="^#[0-9a-fA-F]{6}$" maxlength="7"
                           class="flex-1 rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm uppercase focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <p class="mt-1 text-xs text-gray-400">Warna yang menutupi gambar (default hijau brand).</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Opacity — <span x-text="overlayOpacity + '%'" class="font-mono font-semibold text-primary"></span></label>
                <div class="mt-2 flex items-center gap-3">
                    <input type="range" name="overlay_opacity" min="0" max="100" step="1" x-model.number="overlayOpacity"
                           class="flex-1 accent-primary">
                    <input type="number" min="0" max="100" x-model.number="overlayOpacity"
                           class="w-16 rounded-lg border border-gray-300 px-2 py-2 text-center text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <p class="mt-1 text-xs text-gray-400">0% = gambar terlihat penuh, 100% = warna solid menutup semua.</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 pt-1">
            <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">
                Simpan
            </button>

            @if ($hero['value'])
                <button type="submit" name="remove_hero_background" value="1"
                        onclick="return confirm('Hapus gambar hero {{ $hero['label'] }}? (Warna overlay tetap disimpan)')"
                        class="rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600">
                    Hapus Gambar
                </button>
            @endif

            <a href="{{ $hero['key'] === 'home' ? route('home') : ($hero['key'] === 'catalog' ? route('catalog.index') : route('about')) }}"
               target="_blank"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-cream">
                Buka {{ $hero['label'] }} &nearr;
            </a>
        </div>
    </form>
</div>
