@extends('layouts.app')

@section('title', __('site.projects.title'))
@section('meta_description', __('site.projects.subtitle'))

@push('head')
<style>
    /* Sticky scroll stack — each project section sticks below the
       navbar and the next slides up over it. Higher z-index wins. */
    .project-stack { position: relative; }
    .project-stack section.sticky-scene {
        position: sticky;
        top: 0; /* fills the full viewport, navbar floats above */
        height: 100vh;
        height: 100dvh; /* handle mobile URL-bar collapsing */
        min-height: 620px;
        overflow: hidden;
        /* Full-bleed, rounded on the top corners only */
        border-radius: 2rem 2rem 0 0;
        /* Upward shadow so each card reads as sliding over the previous */
        box-shadow: 0 -20px 45px -12px rgba(0, 0, 0, 0.55);
    }
    /* Intro scene has no rounded top since it's the first surface the user sees */
    .project-stack section.sticky-scene.is-intro {
        border-radius: 0;
        box-shadow: none;
    }
    /* Pull the first project scene up so its rounded top peeks over the bottom
       of the full-height intro hero. Amount scales down at smaller widths so
       tablets and phones still get a subtle hint without eating too much of
       the hero. Sticky snapping to top:0 is unchanged. */
    .project-stack section.sticky-scene.is-intro + .sticky-scene {
        margin-top: -12vh;
    }
    /* Tablet */
    @media (max-width: 1024px) {
        .project-stack section.sticky-scene { min-height: 580px; }
        .project-stack section.sticky-scene.is-intro + .sticky-scene {
            margin-top: -10vh;
        }
    }
    /* Phone */
    @media (max-width: 640px) {
        .project-stack section.sticky-scene {
            border-radius: 1.25rem 1.25rem 0 0;
            min-height: 520px;
        }
        .project-stack section.sticky-scene.is-intro { border-radius: 0; }
        .project-stack section.sticky-scene.is-intro + .sticky-scene {
            margin-top: -7vh;
        }
    }
    @media (min-width: 768px) {
        html { scroll-behavior: smooth; }
    }
    /* Slow ken-burns on the hero image */
    @keyframes kenburns {
        0%, 100% { transform: scale(1.02); }
        50%      { transform: scale(1.10); }
    }
    .ken-burns { animation: kenburns 20s ease-in-out infinite; }
</style>
@endpush

@section('content')

@if ($projects->isNotEmpty())
    {{-- Sticky-stack scroll: intro hero is scene 0; each project slides over the previous --}}
    <div class="project-stack relative -mt-20">
        {{-- Intro hero as the first sticky scene (image + overlay editable via /admin/settings) --}}
        <section class="sticky-scene is-intro" style="z-index: 5">
            @if (!empty($hero['background']))
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
            @else
                <div class="absolute inset-0 bg-gray-900"></div>
            @endif
            <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>

            <div class="relative flex h-full items-center justify-center">
                <div class="text-center text-white px-4 max-w-2xl">
                    <h1 class="text-3xl font-extrabold sm:text-4xl md:text-5xl">{{ __('site.projects.title') }}</h1>
                    <p class="mt-3 text-sm sm:text-base text-white/85">{{ __('site.projects.subtitle') }}</p>
                    <div class="mt-5 sm:mt-6 inline-flex items-center gap-2 text-[10px] sm:text-xs uppercase tracking-widest text-secondary">
                        <span>Scroll ke bawah</span>
                        <svg class="h-4 w-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </div>
                </div>
            </div>
        </section>

        @foreach ($projects as $project)
            <section class="sticky-scene" style="z-index: {{ 10 + $loop->index }}">
                {{-- Background photo with slow ken-burns --}}
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center ken-burns"
                         style="background-image: url('{{ $project->imageUrl() }}');"></div>
                </div>

                {{-- Dark gradient overlay for text readability --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/60 to-black/25"></div>
                <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black/60 to-transparent"></div>

                {{-- Content --}}
                <div class="relative flex h-full items-end">
                    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-12 sm:pb-16 lg:pb-20 text-white">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[10px] sm:text-xs font-semibold uppercase tracking-[0.2em] sm:tracking-[0.3em] text-secondary">
                            <span class="inline-block h-px w-6 sm:w-8 bg-secondary"></span>
                            <span>{{ __('site.projects.title') }}</span>
                            <span class="text-white/60">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad((string) $projects->total(), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <h2 class="mt-3 sm:mt-4 max-w-4xl text-2xl font-extrabold leading-tight sm:text-4xl md:text-5xl lg:text-6xl">
                            <a href="{{ route('projects.show', $project->slug) }}" class="hover:text-secondary transition-colors">
                                {{ $project->translated('title') }}
                            </a>
                        </h2>

                        <div class="mt-4 sm:mt-5 flex flex-wrap items-center gap-x-3 gap-y-2 sm:gap-4 text-xs sm:text-sm text-white/85">
                            @if ($project->year)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $project->year }}
                                </span>
                            @endif
                            @if ($project->location)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $project->location }}
                                </span>
                            @endif
                            @if ($project->client)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $project->client }}
                                </span>
                            @endif
                        </div>

                        @if ($project->translated('short_description'))
                            <p class="mt-4 sm:mt-6 max-w-2xl text-sm sm:text-base md:text-lg text-white/85 line-clamp-3">
                                {{ $project->translated('short_description') }}
                            </p>
                        @endif

                        <a href="{{ route('projects.show', $project->slug) }}"
                           class="mt-6 sm:mt-8 inline-flex items-center gap-2 sm:gap-3 rounded-lg bg-white/10 backdrop-blur px-5 sm:px-6 py-3 sm:py-3.5 text-xs sm:text-sm font-semibold text-white ring-1 ring-white/25 transition-all hover:bg-white hover:text-primary hover:gap-4">
                            {{ __('site.projects.read_more') }}
                            <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Scroll cue for next project (hidden on phone to leave room for content) --}}
                @if (! $loop->last)
                    <div class="pointer-events-none absolute inset-x-0 bottom-4 z-10 hidden sm:flex justify-center text-white/70">
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-[10px] uppercase tracking-widest">Project {{ $loop->iteration + 1 }}</span>
                            <svg class="h-5 w-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </div>
                    </div>
                @endif
            </section>
        @endforeach
    </div>

    {{-- Pagination sits below the stack, on a clean surface --}}
    @if ($projects->hasPages())
        <section class="bg-white py-14">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{ $projects->links() }}
            </div>
        </section>
    @endif
@else
    {{-- Fallback intro hero when no projects exist --}}
    <section class="relative flex min-h-[420px] sm:min-h-[520px] -mt-20 items-center justify-center overflow-hidden pt-20 pb-24 sm:pb-32">
        @if (!empty($hero['background']))
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
        @else
            <div class="absolute inset-0 bg-gray-900"></div>
        @endif
        <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>
        <div class="relative text-center text-white px-4 max-w-2xl">
            <h1 class="text-3xl font-extrabold sm:text-4xl md:text-5xl">{{ __('site.projects.title') }}</h1>
            <p class="mt-3 text-sm sm:text-base text-white/85">{{ __('site.projects.subtitle') }}</p>
        </div>
    </section>

    <section class="bg-white py-16 sm:py-24 px-4">
        <div class="mx-auto max-w-3xl rounded-2xl bg-cream p-8 sm:p-12 lg:p-16 text-center">
            <p class="text-base sm:text-lg font-semibold text-gray-700">{{ __('site.projects.empty_title') }}</p>
            <p class="mt-2 text-sm sm:text-base text-gray-500">{{ __('site.projects.empty_sub') }}</p>
        </div>
    </section>
@endif

@endsection
