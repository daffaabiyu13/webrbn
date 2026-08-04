@php
    // Filename dicari di public/images/partners/. Kalau ada → tampil sebagai gambar.
    // Kalau tidak ada → fallback tampil sebagai teks nama brand.
    // 'size' override tinggi logo per brand karena tiap logo punya
    // aspect-ratio berbeda — Schneider/CHINT dsb dominan text, ABB/Siemens
    // dominan huruf tebal sehingga terlihat lebih besar di tinggi yang sama.
    $partners = [
        ['name' => 'Schneider Electric', 'logo' => 'schneider.png',   'size' => 'h-16 sm:h-20'],
        ['name' => 'CHINT Electrics',    'logo' => 'chint.png',       'size' => 'h-16 sm:h-20'],
        ['name' => 'LS Electric',        'logo' => 'ls-electric.png', 'size' => 'h-14 sm:h-16'],
        ['name' => 'ABB',                'logo' => 'abb.png',         'size' => 'h-10 sm:h-12'],
        ['name' => 'Siemens',            'logo' => 'siemens.png',     'size' => 'h-8 sm:h-10'],
        ['name' => 'Trafindo',           'logo' => 'trafindo.png',    'size' => 'h-12 sm:h-14'],
    ];
@endphp

<section class="border-y border-primary/15 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 fade-up">
        <div class="text-center">
            <h2 class="text-3xl font-semibold text-primary">{{ __('site.partners.title') }}</h2>
            <p class="mt-2 text-gray-600">{{ __('site.partners.sub') }}</p>
        </div>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-8 sm:gap-12 lg:gap-16">
            @foreach ($partners as $partner)
                @php
                    $logoPath = public_path('images/partners/' . $partner['logo']);
                    $hasLogo = file_exists($logoPath);
                    $sizeClass = $partner['size'] ?? 'h-16 sm:h-20';
                @endphp
                <div class="flex h-20 sm:h-24 items-center justify-center">
                    @if ($hasLogo)
                        <img src="{{ asset('images/partners/' . $partner['logo']) }}"
                             alt="{{ $partner['name'] }}"
                             loading="lazy"
                             class="{{ $sizeClass }} w-auto max-w-[200px] object-contain transition-transform hover:scale-105">
                    @else
                        <span class="text-lg font-bold text-primary">{{ $partner['name'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
