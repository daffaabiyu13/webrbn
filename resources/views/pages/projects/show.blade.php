@extends('layouts.app')

@section('title', $project->translated('title'))
@section('meta_description', $project->translated('summary'))

@section('content')

<section class="relative flex min-h-[380px] -mt-20 items-center overflow-hidden pt-20">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $project->imageUrl() }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/50 to-black/70"></div>
    <div class="relative mx-auto max-w-5xl w-full px-4 sm:px-6 lg:px-8 py-16 text-white">
        <nav class="text-sm text-white/80">
            <a href="{{ route('home') }}" class="hover:text-white">{{ __('site.nav.home') }}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('projects.index') }}" class="hover:text-white">{{ __('site.nav.projects') }}</a>
        </nav>
        @if ($project->translated('category'))
            <span class="mt-6 inline-block rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white ring-1 ring-white/30">
                {{ $project->translated('category') }}
            </span>
        @endif
        <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">{{ $project->translated('title') }}</h1>
        <p class="mt-4 max-w-3xl text-lg text-white/85">{{ $project->translated('summary') }}</p>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6 fade-up">
                <div class="overflow-hidden rounded-2xl ring-1 ring-gray-100">
                    <img src="{{ $project->imageUrl() }}" alt="{{ $project->translated('title') }}" class="w-full">
                </div>

                @if ($project->formattedDescription())
                    <div class="prose max-w-none prose-p:text-gray-700 prose-p:leading-relaxed prose-strong:text-[#1A1A1A]">
                        {!! $project->formattedDescription() !!}
                    </div>
                @endif
            </div>

            <aside class="lg:pl-4 fade-up">
                <div class="sticky top-24 rounded-2xl bg-offwhite p-6 ring-1 ring-gray-100">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-primary">
                        {{ __('site.projects.meta_title') }}
                    </h2>
                    <dl class="mt-4 space-y-4 text-sm">
                        @if ($project->client)
                            <div>
                                <dt class="font-semibold text-gray-500 uppercase text-xs">
                                    {{ __('site.projects.meta_client') }}
                                </dt>
                                <dd class="mt-1 text-gray-800">{{ $project->client }}</dd>
                            </div>
                        @endif
                        @if ($project->location)
                            <div>
                                <dt class="font-semibold text-gray-500 uppercase text-xs">
                                    {{ __('site.projects.meta_location') }}
                                </dt>
                                <dd class="mt-1 text-gray-800">{{ $project->location }}</dd>
                            </div>
                        @endif
                        @if ($project->year)
                            <div>
                                <dt class="font-semibold text-gray-500 uppercase text-xs">
                                    {{ __('site.projects.meta_year') }}
                                </dt>
                                <dd class="mt-1 text-gray-800">{{ $project->year }}</dd>
                            </div>
                        @endif
                        @if ($project->translated('category'))
                            <div>
                                <dt class="font-semibold text-gray-500 uppercase text-xs">
                                    {{ __('site.projects.meta_category') }}
                                </dt>
                                <dd class="mt-1 text-gray-800">{{ $project->translated('category') }}</dd>
                            </div>
                        @endif
                    </dl>

                    <a href="mailto:pt.radikabintang@gmail.com?subject={{ urlencode(__('site.projects.meta_inquiry', ['title' => $project->translated('title')])) }}"
                       class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-primary-dark transition-colors">
                        {{ __('site.projects.meta_cta') }}
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

@if ($related->isNotEmpty())
    <section class="bg-offwhite py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between fade-up">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wider text-secondary">
                        {{ __('site.projects.related_label') }}
                    </span>
                    <h2 class="mt-1 text-2xl font-bold text-[#1A1A1A]">{{ __('site.projects.related_title') }}</h2>
                </div>
                <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-primary hover:text-primary-dark">
                    {{ __('site.projects.related_more') }} →
                </a>
            </div>
            <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ($related as $rel)
                    <a href="{{ route('projects.show', $rel) }}"
                       class="group flex flex-col overflow-hidden rounded-2xl bg-white ring-1 ring-gray-100 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg fade-up">
                        <div class="aspect-[4/3] w-full overflow-hidden bg-offwhite">
                            <img src="{{ $rel->imageUrl() }}" alt="{{ $rel->translated('title') }}"
                                 class="h-full w-full object-cover transition-transform group-hover:scale-105" loading="lazy">
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-gray-500">{{ $rel->translated('category') }} · {{ $rel->year }}</p>
                            <h3 class="mt-2 font-semibold text-[#1A1A1A] line-clamp-2 group-hover:text-primary transition-colors">
                                {{ $rel->translated('title') }}
                            </h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
