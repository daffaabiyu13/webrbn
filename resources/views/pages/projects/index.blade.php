@extends('layouts.app')

@section('title', __('site.projects.title'))
@section('meta_description', __('site.projects.subtitle'))

@section('content')

{{-- Page hero --}}
<section class="relative flex h-[380px] -mt-20 items-center justify-center overflow-hidden pt-20">
    @if (!empty($hero['background']))
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
    @else
        <div class="absolute inset-0 bg-gray-900"></div>
    @endif
    <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>
    <div class="relative text-center text-white px-4">
        <h1 class="text-4xl font-extrabold sm:text-5xl">{{ __('site.projects.title') }}</h1>
        <p class="mt-3 text-white/80 max-w-2xl mx-auto">{{ __('site.projects.subtitle') }}</p>
        <nav class="mt-3 text-sm text-white/80">
            <a href="{{ route('home') }}" class="hover:text-secondary">{{ __('site.catalog.breadcrumb_home') }}</a>
            <span class="mx-2">/</span>
            <span class="text-secondary">{{ __('site.projects.title') }}</span>
        </nav>
    </div>
</section>

<section class="bg-white py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if ($projects->isNotEmpty())
            <div class="grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    <article class="group flex flex-col overflow-hidden rounded-xl bg-white shadow-md ring-1 ring-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        @include('partials.skeleton-image', [
                            'src' => $project->imageUrl(),
                            'alt' => $project->translated('title'),
                            'wrapClass' => 'relative aspect-[16/10] overflow-hidden bg-cream',
                            'imgClass' => 'h-full w-full object-cover transition-transform duration-500 group-hover:scale-105',
                        ])

                        <div class="flex flex-1 flex-col p-5">
                            @if ($project->year || $project->location)
                                <div class="flex flex-wrap gap-2 text-[10px] uppercase tracking-wider text-gray-500">
                                    @if ($project->year)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-secondary/15 px-2 py-0.5 font-semibold text-primary">{{ $project->year }}</span>
                                    @endif
                                    @if ($project->location)
                                        <span class="inline-flex items-center gap-1 text-gray-500">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $project->location }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <h3 class="mt-3 text-lg font-bold text-[#1A1A1A] leading-snug">{{ $project->translated('title') }}</h3>

                            @if ($project->client)
                                <p class="mt-1 text-xs text-gray-500">Client: <span class="font-medium text-gray-700">{{ $project->client }}</span></p>
                            @endif

                            @if ($project->translated('short_description'))
                                <p class="mt-3 text-sm text-gray-600 line-clamp-4">{{ $project->translated('short_description') }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $projects->links() }}
            </div>
        @else
            <div class="rounded-2xl bg-cream p-16 text-center">
                <p class="text-lg font-semibold text-gray-700">{{ __('site.projects.empty_title') }}</p>
                <p class="mt-2 text-gray-500">{{ __('site.projects.empty_sub') }}</p>
            </div>
        @endif
    </div>
</section>

@endsection
