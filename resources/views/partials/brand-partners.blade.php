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

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4 sm:gap-6">
            @foreach ($partners as $partner)
                @php
                    $logoPath = public_path('images/partners/' . $partner['logo']);
                    $hasLogo = file_exists($logoPath);
                @endphp
                <div class="flex h-20 min-w-[160px] items-center justify-center rounded-xl border border-gray-200 bg-white px-6 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                    @if ($hasLogo)
                        <img src="{{ asset('images/partners/' . $partner['logo']) }}"
                             alt="{{ $partner['name'] }}"
                             loading="lazy"
                             class="max-h-12 max-w-[140px] object-contain grayscale opacity-80 transition-all hover:grayscale-0 hover:opacity-100">
                    @else
                        <span class="text-base font-bold text-primary">{{ $partner['name'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
