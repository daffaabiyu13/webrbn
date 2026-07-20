{{-- Expects: $hero = ['key' => 'home', 'label' => 'Home', 'value' => '...'] --}}
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
            <h2 class="text-base font-semibold text-gray-800">Hero Background — {{ $hero['label'] }}</h2>
            <p class="mt-1 text-sm text-gray-500">Muncul di belakang teks hero pada halaman <b>/{{ $hero['key'] === 'home' ? '' : $hero['key'] }}</b>.</p>
        </div>
        <span class="rounded-full bg-secondary/15 px-3 py-1 text-xs font-semibold text-primary">{{ ucfirst($hero['key']) }}</span>
    </div>

    {{-- Preview area with overlay --}}
    <div class="mt-5">
        <div class="relative overflow-hidden rounded-xl ring-1 ring-gray-200 h-56">
            <template x-if="preview">
                <img :src="preview" alt="Preview" class="absolute inset-0 h-full w-full object-cover">
            </template>
            <template x-if="!preview">
                @if ($hero['value'])
                    <img src="{{ asset('storage/' . $hero['value']) }}" alt="Hero background" class="absolute inset-0 h-full w-full object-cover">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-dark via-primary to-[#0d2a0b]"></div>
                @endif
            </template>

            <div class="absolute inset-0 bg-gradient-to-br from-primary-dark/85 via-primary/75 to-[#0d2a0b]/85"></div>
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
        @if (! $hero['value'])
            <p class="mt-2 text-xs text-gray-500" x-show="!preview">Default gradient (belum ada gambar).</p>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.settings.hero.update', ['page' => $hero['key']]) }}" enctype="multipart/form-data" class="mt-5 space-y-3">
        @csrf

        <input type="file" name="hero_background" accept="image/*" x-ref="file" @change="handle($event)"
               class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark">
        <p class="text-xs text-gray-400">JPG/PNG/WebP. Otomatis di-resize (max 2400x1600) &amp; dikompres JPEG. Batas server: <b>{{ $limit->human() }}</b>.</p>

        <div class="flex flex-wrap gap-2 pt-1">
            <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">
                Simpan
            </button>

            @if ($hero['value'])
                <button type="submit" name="remove_hero_background" value="1"
                        onclick="return confirm('Hapus hero background {{ $hero['label'] }}?')"
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
