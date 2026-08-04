@extends('layouts.app')

@section('title', __('site.nav.about'))
@section('meta_description', __('site.about.profile_p1'))

@section('content')

<section class="relative flex min-h-[420px] -mt-20 items-center overflow-hidden pt-20">
    @if (!empty($hero['background']))
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
    @else
        <div class="absolute inset-0 bg-gray-900"></div>
    @endif
    <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>
    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-20 text-center text-white">
        <h1 class="text-4xl font-extrabold sm:text-5xl">{{ __('site.about.hero_title') }}</h1>
        <p class="mt-4 text-lg text-secondary">{{ __('site.about.hero_sub') }}</p>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center fade-up">
        <span class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.about.profile_label') }}</span>
        <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">{{ __('site.about.profile_title') }}</h2>
        <p class="mt-6 text-gray-600 leading-relaxed">{{ __('site.about.profile_p1') }}</p>
        <p class="mt-4 text-gray-600 leading-relaxed">{{ __('site.about.profile_p2') }}</p>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-2xl bg-white p-8 shadow-md ring-1 ring-gray-100 fade-up">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.46 12C3.73 7.94 7.52 5 12 5c4.48 0 8.27 2.94 9.54 7-1.27 4.06-5.06 7-9.54 7-4.48 0-8.27-2.94-9.54-7z"/></svg>
                </span>
                <h3 class="mt-5 text-2xl font-bold text-primary">{{ __('site.about.vision') }}</h3>
                <p class="mt-3 text-gray-600 leading-relaxed">{{ __('site.about.vision_body') }}</p>
            </div>

            <div class="rounded-2xl bg-white p-8 shadow-md ring-1 ring-gray-100 fade-up">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
                <h3 class="mt-5 text-2xl font-bold text-primary">{{ __('site.about.mission') }}</h3>
                <p class="mt-3 text-gray-600 leading-relaxed">{{ __('site.about.mission_body') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.about.values_label') }}</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">{{ __('site.about.values_title') }}</h2>
        </div>
        <div class="mt-12 grid grid-cols-1 gap-7 md:grid-cols-3">
            @foreach ([
                [__('site.about.value_1_title'), __('site.about.value_1_body')],
                [__('site.about.value_2_title'), __('site.about.value_2_body')],
                [__('site.about.value_3_title'), __('site.about.value_3_body')],
            ] as $i => $value)
                <div class="rounded-2xl bg-offwhite p-7 ring-1 ring-gray-100 fade-up">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-primary text-lg font-bold text-white">{{ $i + 1 }}</span>
                    <h3 class="mt-5 text-lg font-bold text-[#1A1A1A]">{{ $value[0] }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ $value[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.about.data_label') }}</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">{{ __('site.about.data_title') }}</h2>
        </div>
        <div class="mt-10 overflow-hidden rounded-2xl bg-white shadow-md ring-1 ring-gray-100 fade-up">
            <dl class="divide-y divide-gray-100">
                @foreach ([
                    [__('site.about.data_head_office'), __('site.about.data_head_office_addr')],
                    [__('site.about.data_branch'), __('site.about.data_branch_addr')],
                    [__('site.about.data_legal'), 'Nurul Latifah, SH., M.Kn'],
                    ['NIB', '1003250060023'],
                    ['NPWP', '1000 0000 0073 4477'],
                    [__('site.about.data_email'), 'pt.radikabintang@gmail.com'],
                    [__('site.about.data_phone'), '+62 853-3033-0396, +62 821-2334-5758'],
                ] as $row)
                    <div class="grid grid-cols-1 gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-semibold text-gray-700">{{ $row[0] }}</dt>
                        <dd class="text-sm text-gray-600 sm:col-span-2">{{ $row[1] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.about.segments_label') }}</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">{{ __('site.about.segments_title') }}</h2>
        </div>
        <div class="mt-12 grid grid-cols-2 gap-5 md:grid-cols-5 fade-up">
            @foreach ([
                [__('site.home.industry_oil_gas'), 'M3 13l2-7h14l2 7M5 13h14v6a1 1 0 01-1 1H6a1 1 0 01-1-1v-6z'],
                [__('site.home.industry_mining'), 'M3 21l6-6m0 0l4-9 8 8-9 4m-3 1l-1-1'],
                [__('site.home.industry_building'), 'M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01'],
                [__('site.home.industry_fnb'), 'M5 3v18M5 8h6M11 3v18M16 3c2 2 2 6 0 8v10'],
                [__('site.home.industry_water'), 'M12 3l5.66 5.66a8 8 0 11-11.32 0L12 3z'],
            ] as $industry)
                <div class="flex flex-col items-center justify-center rounded-2xl bg-offwhite p-6 text-center ring-1 ring-gray-100">
                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $industry[1] }}"/></svg>
                    </span>
                    <span class="mt-2 text-sm font-semibold text-gray-800">{{ $industry[0] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.about.cert_label') }}</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">{{ __('site.about.cert_title') }}</h2>
        </div>
        <div class="mt-10 overflow-hidden rounded-2xl bg-white shadow-md ring-1 ring-gray-100 fade-up">
            <div class="bg-primary px-8 py-6 text-white">
                <p class="text-sm uppercase tracking-wider text-secondary">Schneider Electric</p>
                <h3 class="mt-1 text-2xl font-bold">Authorized Partner</h3>
            </div>

            @if (!empty($certificate))
                <a href="{{ $certificate }}" target="_blank" rel="noopener" class="block bg-cream/40 px-4 py-6 transition-colors hover:bg-cream/70">
                    @include('partials.skeleton-image', [
                        'src' => $certificate,
                        'alt' => __('site.about.cert_title'),
                        'wrapClass' => 'relative overflow-hidden rounded-lg bg-white ring-1 ring-gray-200 max-w-3xl mx-auto',
                        'imgClass' => 'w-full h-auto object-contain',
                    ])
                    <p class="mt-3 text-center text-xs text-gray-500">Klik untuk membuka gambar penuh</p>
                </a>
            @endif

            <div class="p-8">
                <p class="text-lg font-semibold text-gray-800">{{ __('site.about.cert_partner_name') }}</p>
                <p class="mt-1 text-gray-600">{{ __('site.about.cert_partner_role') }}</p>
                <p class="mt-4 inline-flex items-center gap-2 rounded-lg bg-secondary/15 px-4 py-2 text-sm font-semibold text-primary">
                    {{ __('site.about.cert_valid') }}
                </p>
                <p class="mt-5 text-sm text-gray-500">{{ __('site.about.cert_signed') }}</p>
            </div>
        </div>
    </div>
</section>

@include('partials.brand-partners')

@endsection
