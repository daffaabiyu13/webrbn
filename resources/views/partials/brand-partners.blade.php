@php
    // Filename dicari di public/images/partners/. Kalau ada → tampil sebagai gambar.
    // Kalau tidak ada → fallback tampil sebagai teks nama brand.
    $partners = [
        ['name' => 'Schneider Electric', 'logo' => 'schneider.png'],
        ['name' => 'CHINT Electrics',    'logo' => 'chint.png'],
        ['name' => 'LS Electric',        'logo' => 'ls-electric.png'],
        ['name' => 'EBARA',              'logo' => 'ebara.png'],
        ['name' => 'Trafindo',           'logo' => 'trafindo.png'],
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
                @endphp
                <div class="flex items-center justify-center">
                    @if ($hasLogo)
                        <img src="{{ asset('images/partners/' . $partner['logo']) }}"
                             alt="{{ $partner['name'] }}"
                             loading="lazy"
                             class="h-20 sm:h-24 w-auto max-w-[200px] object-contain transition-transform hover:scale-105">
                    @else
                        <span class="text-lg font-bold text-primary">{{ $partner['name'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
