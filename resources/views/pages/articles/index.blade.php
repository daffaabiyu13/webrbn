@extends('layouts.app')

@section('title', __('site.articles.title'))
@section('meta_description', __('site.articles.subtitle'))

@push('head')
<style>
    /* Sticky scroll stack — each article section sticks below the
       navbar and the next slides up over it. Higher z-index wins. */
    .article-stack { position: relative; }
    .article-stack section.sticky-scene {
        position: sticky;
        top: 0;
        height: 100vh;
        min-height: 620px;
        overflow: hidden;
        border-radius: 2rem 2rem 0 0;
        box-shadow: 0 -20px 45px -12px rgba(0, 0, 0, 0.55);
    }
    .article-stack section.sticky-scene.is-intro {
        border-radius: 0;
        box-shadow: none;
    }
    /* Pull the first article scene up slightly so its rounded top peeks over
       the bottom of the full-height intro hero — a small affordance that
       hints there's more content below. The scene still snaps to top:0
       once the user scrolls, so the stack behavior is unchanged. */
    .article-stack section.sticky-scene.is-intro + .sticky-scene {
        margin-top: -12vh;
    }
    @media (max-width: 640px) {
        .article-stack section.sticky-scene {
            border-radius: 1.5rem 1.5rem 0 0;
            min-height: 560px;
        }
        .article-stack section.sticky-scene.is-intro { border-radius: 0; }
        .article-stack section.sticky-scene.is-intro + .sticky-scene {
            margin-top: -10vh;
        }
    }
    @media (min-width: 768px) {
        html { scroll-behavior: smooth; }
    }
    @keyframes kenburns {
        0%, 100% { transform: scale(1.02); }
        50%      { transform: scale(1.10); }
    }
    .ken-burns { animation: kenburns 20s ease-in-out infinite; }
</style>
@endpush

@section('content')

@if ($articles->isNotEmpty())
    {{-- Sticky-stack scroll: intro hero is scene 0; each article slides over the previous --}}
    <div class="article-stack relative -mt-20">
        {{-- Intro hero as the first sticky scene (image + overlay editable via /admin/settings) --}}
        <section class="sticky-scene is-intro" style="z-index: 5">
            @if (!empty($hero['background']))
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
            @else
                <div class="absolute inset-0 bg-gray-900"></div>
            @endif
            <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>

            <div class="relative flex h-full items-center justify-center">
                <div class="text-center text-white px-4">
                    <h1 class="text-4xl font-extrabold sm:text-5xl">{{ __('site.articles.title') }}</h1>
                    <p class="mt-3 text-white/85 max-w-2xl mx-auto">{{ __('site.articles.subtitle') }}</p>
                    <div class="mt-6 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-secondary">
                        <span>Scroll ke bawah</span>
                        <svg class="h-4 w-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </div>
                </div>
            </div>
        </section>

        @foreach ($articles as $article)
            <section class="sticky-scene" style="z-index: {{ 10 + $loop->index }}">
                {{-- Background photo with slow ken-burns --}}
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center ken-burns"
                         style="background-image: url('{{ $article->imageUrl() }}');"></div>
                </div>

                {{-- Dark gradient overlay for text readability --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/60 to-black/25"></div>
                <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black/60 to-transparent"></div>

                {{-- Full-scene link to article detail. Any click on the scene (except the
                     "Baca Selengkapnya" button, which has higher z-index) navigates here. --}}
                <a href="{{ route('articles.show', $article->slug) }}"
                   class="absolute inset-0 z-[5]"
                   aria-label="{{ __('site.articles.view_detail') }}: {{ $article->translated('title') }}"></a>

                {{-- Content — pointer-events pass through to the link overlay above,
                     the read-more CTA re-enables pointer events for itself. --}}
                <div class="relative flex h-full items-end pointer-events-none">
                    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-16 sm:pb-20 text-white">
                        <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-secondary">
                            <span class="inline-block h-px w-8 bg-secondary"></span>
                            <span>{{ __('site.articles.title') }}</span>
                            <span class="text-white/60">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad((string) $articles->total(), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <h2 class="mt-4 max-w-4xl text-3xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
                            {{ $article->translated('title') }}
                        </h2>

                        <div class="mt-5 flex flex-wrap items-center gap-4 text-sm text-white/85">
                            @if ($article->year)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $article->year }}
                                </span>
                            @endif
                            @if ($article->location)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $article->location }}
                                </span>
                            @endif
                            @if ($article->client)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $article->client }}
                                </span>
                            @endif
                        </div>

                        @if ($article->translated('short_description'))
                            <p class="mt-6 max-w-2xl text-base text-white/85 sm:text-lg line-clamp-3">
                                {{ $article->translated('short_description') }}
                            </p>
                        @endif

                        @if ($article->url)
                            <a href="{{ $article->url }}" target="_blank" rel="noopener"
                               class="pointer-events-auto relative z-20 mt-8 inline-flex items-center gap-3 rounded-lg bg-white/10 backdrop-blur px-6 py-3.5 text-sm font-semibold text-white ring-1 ring-white/25 transition-all hover:bg-white hover:text-primary hover:gap-4">
                                {{ __('site.articles.read_more') }}
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Scroll cue for next article (not on last one) --}}
                @if (! $loop->last)
                    <div class="pointer-events-none absolute inset-x-0 bottom-4 z-10 flex justify-center text-white/70">
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-[10px] uppercase tracking-widest">{{ __('site.articles.title') }} {{ $loop->iteration + 1 }}</span>
                            <svg class="h-5 w-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </div>
                    </div>
                @endif
            </section>
        @endforeach
    </div>

    {{-- Pagination sits below the stack, on a clean surface --}}
    @if ($articles->hasPages())
        <section class="bg-white py-14">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{ $articles->links() }}
            </div>
        </section>
    @endif
@else
    {{-- Fallback intro hero when no articles exist --}}
    <section class="relative flex min-h-[520px] -mt-20 items-center justify-center overflow-hidden pt-20 pb-32">
        @if (!empty($hero['background']))
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
        @else
            <div class="absolute inset-0 bg-gray-900"></div>
        @endif
        <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>
        <div class="relative text-center text-white px-4">
            <h1 class="text-4xl font-extrabold sm:text-5xl">{{ __('site.articles.title') }}</h1>
            <p class="mt-3 text-white/85 max-w-2xl mx-auto">{{ __('site.articles.subtitle') }}</p>
        </div>
    </section>

    <section class="bg-white py-24">
        <div class="mx-auto max-w-3xl rounded-2xl bg-cream p-16 text-center">
            <p class="text-lg font-semibold text-gray-700">{{ __('site.articles.empty_title') }}</p>
            <p class="mt-2 text-gray-500">{{ __('site.articles.empty_sub') }}</p>
        </div>
    </section>
@endif

@endsection
