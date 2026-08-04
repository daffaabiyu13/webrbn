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
        top: 5rem; /* sits directly below the 80px navbar */
        height: calc(100vh - 5rem);
        min-height: 560px;
        overflow: hidden;
        /* Full-bleed, rounded on the top corners only */
        border-radius: 2rem 2rem 0 0;
        /* Upward shadow so each card reads as sliding over the previous */
        box-shadow: 0 -20px 45px -12px rgba(0, 0, 0, 0.55);
    }
    @media (max-width: 640px) {
        .project-stack section.sticky-scene {
            border-radius: 1.5rem 1.5rem 0 0;
            min-height: 520px;
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

{{-- Intro hero (image + overlay editable via /admin/settings) --}}
<section class="relative flex h-[420px] -mt-20 items-end justify-center overflow-hidden pt-20">
    @if (!empty($hero['background']))
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
    @else
        <div class="absolute inset-0 bg-gray-900"></div>
    @endif
    <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>

    <div class="relative text-center text-white px-4 pb-12">
        <h1 class="text-4xl font-extrabold sm:text-5xl">{{ __('site.projects.title') }}</h1>
        <p class="mt-3 text-white/85 max-w-2xl mx-auto">{{ __('site.projects.subtitle') }}</p>
        <div class="mt-6 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-secondary">
            <span>Scroll ke bawah</span>
            <svg class="h-4 w-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </div>
</section>

@if ($projects->isNotEmpty())
    {{-- Sticky-stack scroll: each project fills the viewport, next slides over --}}
    <div class="project-stack bg-offwhite">
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
                    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-16 sm:pb-20 text-white">
                        <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-secondary">
                            <span class="inline-block h-px w-8 bg-secondary"></span>
                            <span>{{ __('site.projects.title') }}</span>
                            <span class="text-white/60">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad((string) $projects->total(), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <h2 class="mt-4 max-w-4xl text-3xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
                            <a href="{{ route('projects.show', $project->slug) }}" class="hover:text-secondary transition-colors">
                                {{ $project->translated('title') }}
                            </a>
                        </h2>

                        <div class="mt-5 flex flex-wrap items-center gap-4 text-sm text-white/85">
                            @if ($project->year)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $project->year }}
                                </span>
                            @endif
                            @if ($project->location)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $project->location }}
                                </span>
                            @endif
                            @if ($project->client)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $project->client }}
                                </span>
                            @endif
                        </div>

                        @if ($project->translated('short_description'))
                            <p class="mt-6 max-w-2xl text-base text-white/85 sm:text-lg line-clamp-3">
                                {{ $project->translated('short_description') }}
                            </p>
                        @endif

                        <a href="{{ route('projects.show', $project->slug) }}"
                           class="mt-8 inline-flex items-center gap-3 rounded-lg bg-white/10 backdrop-blur px-6 py-3.5 text-sm font-semibold text-white ring-1 ring-white/25 transition-all hover:bg-white hover:text-primary hover:gap-4">
                            {{ __('site.projects.read_more') }}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Scroll cue for next project (not on last one) --}}
                @if (! $loop->last)
                    <div class="pointer-events-none absolute inset-x-0 bottom-4 z-10 flex justify-center text-white/70">
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
    <section class="bg-white py-24">
        <div class="mx-auto max-w-3xl rounded-2xl bg-cream p-16 text-center">
            <p class="text-lg font-semibold text-gray-700">{{ __('site.projects.empty_title') }}</p>
            <p class="mt-2 text-gray-500">{{ __('site.projects.empty_sub') }}</p>
        </div>
    </section>
@endif

@endsection
