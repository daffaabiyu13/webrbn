@extends('layouts.app')

@section('title', $project->translated('title'))
@section('meta_description', Str::limit(strip_tags($project->translated('short_description') ?? ''), 155))

@section('content')

{{-- Hero with project image --}}
<section class="relative flex min-h-[520px] -mt-20 items-end overflow-hidden pt-20">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $project->imageUrl() }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/50 to-black/25"></div>

    <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-14 pt-24 text-white">
        <h1 class="text-3xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
            {{ $project->translated('title') }}
        </h1>
        <nav class="mt-4 text-sm text-white/80">
            <a href="{{ route('home') }}" class="hover:text-secondary">{{ __('site.projects.breadcrumb_home') }}</a>
            <span class="mx-2">•</span>
            <a href="{{ route('projects.index') }}" class="hover:text-secondary">{{ __('site.projects.breadcrumb_projects') }}</a>
            <span class="mx-2">•</span>
            <span class="text-secondary">{{ Str::limit($project->translated('title'), 60) }}</span>
        </nav>
    </div>
</section>

{{-- Metadata bar — floating card that overlaps the hero bottom edge --}}
<section class="relative -mt-16 sm:-mt-20 z-10 fade-up">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-primary text-white shadow-2xl ring-1 ring-primary-dark/40">
            <dl class="grid grid-cols-1 divide-y divide-white/15 sm:grid-cols-3 sm:divide-y-0 sm:divide-x">
                <div class="py-6 sm:px-6 text-center slide-in-left" style="--reveal-delay: 100ms">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-secondary">{{ __('site.projects.meta_location') }}</dt>
                    <dd class="mt-2 text-lg font-bold sm:text-xl">{{ $project->location ?: '—' }}</dd>
                </div>
                <div class="py-6 sm:px-6 text-center fade-up" style="--reveal-delay: 200ms">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-secondary">{{ __('site.projects.meta_size') }}</dt>
                    <dd class="mt-2 text-lg font-bold sm:text-xl">{{ $project->project_size ?: '—' }}</dd>
                </div>
                <div class="py-6 sm:px-6 text-center slide-in-right" style="--reveal-delay: 300ms">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-secondary">{{ __('site.projects.meta_year') }}</dt>
                    <dd class="mt-2 text-lg font-bold sm:text-xl">{{ $project->year ?: '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</section>

{{-- Content — pulls up under the floating metadata card, no visible seam --}}
<section class="relative bg-white pt-24 pb-16">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 fade-up">
        @if ($project->client)
            <div class="mb-8 flex items-center gap-3">
                <span class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.projects.meta_client') }}</span>
                <span class="text-lg font-bold text-primary">{{ $project->client }}</span>
            </div>
        @endif

        <div class="prose max-w-none text-gray-700 leading-relaxed [&_p]:mb-4 [&_strong]:text-gray-900">
            {!! $project->formattedDescription() !!}
        </div>
    </div>
</section>

{{-- Related projects --}}
@if ($related->isNotEmpty())
    <section class="bg-offwhite py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-[#1A1A1A] sm:text-3xl">{{ __('site.projects.related_title') }}</h2>

            <div class="mt-8 grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $item)
                    @php
                        $col = $loop->index % 3;
                        $slideClass = $col === 0 ? 'slide-in-left' : ($col === 2 ? 'slide-in-right' : 'fade-up');
                    @endphp
                    <article class="group {{ $slideClass }} flex flex-col overflow-hidden rounded-xl bg-white shadow-md ring-1 ring-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                             style="--reveal-delay: {{ $loop->index * 120 }}ms">
                        <a href="{{ route('projects.show', $item->slug) }}" class="block">
                            @include('partials.skeleton-image', [
                                'src' => $item->imageUrl(),
                                'alt' => $item->translated('title'),
                                'wrapClass' => 'relative aspect-[16/10] overflow-hidden bg-cream',
                                'imgClass' => 'h-full w-full object-cover transition-transform duration-500 group-hover:scale-105',
                            ])
                        </a>
                        <div class="flex flex-1 flex-col p-5">
                            @if ($item->year)
                                <span class="inline-flex w-fit rounded-full bg-secondary/15 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-primary">{{ $item->year }}</span>
                            @endif
                            <h3 class="mt-3 text-base font-bold text-[#1A1A1A] leading-snug">
                                <a href="{{ route('projects.show', $item->slug) }}" class="hover:text-primary transition-colors">
                                    {{ $item->translated('title') }}
                                </a>
                            </h3>
                            @if ($item->translated('short_description'))
                                <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $item->translated('short_description') }}</p>
                            @endif
                            <a href="{{ route('projects.show', $item->slug) }}"
                               class="mt-4 inline-flex items-center gap-2 self-start text-xs font-semibold text-primary hover:gap-3 transition-all">
                                {{ __('site.projects.read_more') }}
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
