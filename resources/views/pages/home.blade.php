@extends('layouts.app')

@section('title', __('site.home_hero.subtitle'))
@section('meta_description', __('site.home_hero.lead'))

@section('content')

{{-- SECTION 1: HERO --}}
<section class="relative flex flex-col min-h-[90vh] overflow-hidden">
    @if (!empty($hero['background']))
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
    @else
        <div class="absolute inset-0 bg-gray-900"></div>
    @endif
    <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>

    {{-- Main content — flex-1 so it fills remaining space above stats --}}
    <div class="relative flex flex-1 items-center">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center text-white">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs sm:text-sm font-medium ring-1 ring-white/20 backdrop-blur">
                <svg class="h-4 w-4 text-secondary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.3 3.29 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                Authorized Schneider Electric Partner
            </span>

            <h1 class="mt-6 text-3xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">{{ __('site.home_hero.title') }}</h1>
            <h2 class="mt-3 text-lg font-medium text-secondary sm:text-2xl">{{ __('site.home_hero.subtitle') }}</h2>
            <p class="mx-auto mt-6 max-w-2xl text-sm text-white/80 sm:text-lg">
                {{ __('site.home_hero.lead') }}
            </p>

            <div class="mt-8 flex w-full flex-col items-stretch justify-center gap-3 sm:w-auto sm:flex-row sm:items-center sm:gap-4">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-secondary px-6 py-3.5 text-sm sm:text-base font-semibold text-white shadow-lg transition-colors hover:bg-[#5aa838]">
                    {{ __('site.home_hero.cta_catalog') }}
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-white/40 px-6 py-3.5 text-sm sm:text-base font-semibold text-white transition-colors hover:bg-white hover:text-primary">
                    {{ __('site.home_hero.cta_about') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Hero statistics — normal flow so it never overlaps content --}}
    <div class="relative border-t border-white/10 bg-black/20 backdrop-blur">
        <div class="mx-auto grid max-w-5xl grid-cols-2 divide-x divide-y divide-white/10 sm:divide-y-0 sm:grid-cols-4">
            @foreach ([
                [__('site.home_hero.stat_established'), __('site.home_hero.stat_established_val')],
                [__('site.home_hero.stat_offices'), __('site.home_hero.stat_offices_val')],
                [__('site.home_hero.stat_industries'), __('site.home_hero.stat_industries_val')],
                [__('site.home_hero.stat_partner'), __('site.home_hero.stat_partner_val')],
            ] as $stat)
                <div class="px-3 py-5 sm:py-6 text-center text-white">
                    <div class="text-lg sm:text-2xl font-extrabold text-secondary">{{ $stat[1] }}</div>
                    <div class="mt-1 text-[10px] sm:text-xs uppercase tracking-wider text-white/70">{{ $stat[0] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 2: BEST PRODUCTS --}}
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.home.featured_label') }}</span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A] sm:text-4xl">{{ __('site.home.featured_title') }}</h2>
            <p class="mt-3 text-gray-600">{{ __('site.home.featured_sub') }}</p>
        </div>

        @if ($featuredProducts->isNotEmpty())
            <div class="mt-12 grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3 fade-up">
                @foreach ($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <p class="mt-12 text-center text-gray-500">{{ __('site.home.featured_empty') }}</p>
        @endif

        <div class="mt-12 text-center fade-up">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-primary-dark">
                {{ __('site.home.featured_view_all') }}
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- SECTION 3: COMPANY SNAPSHOT --}}
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div class="fade-up">
                <span class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.home.about_label') }}</span>
                <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A] sm:text-4xl">{{ __('site.home.about_title') }}</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    {{ __('site.home.about_lead') }}
                </p>

                <ul class="mt-6 space-y-3">
                    @foreach ([
                        __('site.home.highlight_1'),
                        __('site.home.highlight_2'),
                        __('site.home.highlight_3'),
                        __('site.home.highlight_4'),
                    ] as $point)
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-6 w-6 flex-none items-center justify-center rounded-full bg-secondary/15 text-primary">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.3 3.29 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                            </span>
                            <span class="text-gray-700">{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('about') }}" class="mt-8 inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-primary-dark">
                    {{ __('site.home.about_more') }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-5 fade-up">
                @foreach ([
                    [__('site.home.industry_oil_gas'), 'M3 13l2-7h14l2 7M5 13h14v6a1 1 0 01-1 1H6a1 1 0 01-1-1v-6z'],
                    [__('site.home.industry_mining'), 'M3 21l6-6m0 0l4-9 8 8-9 4m-3 1l-1-1'],
                    [__('site.home.industry_building'), 'M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01'],
                    [__('site.home.industry_fnb'), 'M5 3v18M5 8h6M11 3v18M16 3c2 2 2 6 0 8v10'],
                ] as $industry)
                    <div class="flex flex-col items-center justify-center rounded-2xl bg-offwhite p-8 text-center shadow-sm ring-1 ring-gray-100">
                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $industry[1] }}"/></svg>
                        </span>
                        <span class="mt-2 font-semibold text-gray-800">{{ $industry[0] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@include('partials.brand-partners')

@endsection
