@extends('layouts.app')

@section('title', __('site.nav.projects'))
@section('meta_description', __('site.projects.hero_sub'))

@section('content')

<section class="relative flex min-h-[420px] -mt-20 items-center overflow-hidden pt-20">
    @if (!empty($hero['background']))
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero['background'] }}');"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-primary-dark to-primary"></div>
    @endif
    <div class="absolute inset-0" style="background-color: {{ $hero['overlay_rgba'] }};"></div>
    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-20 text-center text-white">
        <span class="inline-block text-sm font-semibold uppercase tracking-wider text-secondary">
            {{ __('site.projects.hero_label') }}
        </span>
        <h1 class="mt-2 text-4xl font-extrabold sm:text-5xl">{{ __('site.projects.hero_title') }}</h1>
        <p class="mt-4 text-lg text-white/85">{{ __('site.projects.hero_sub') }}</p>
    </div>
</section>

<section class="bg-offwhite py-20" x-data="{ active: 'all' }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-up">
            <span class="text-sm font-semibold uppercase tracking-wider text-secondary">
                {{ __('site.projects.list_label') }}
            </span>
            <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">{{ __('site.projects.list_title') }}</h2>
            <p class="mx-auto mt-4 max-w-2xl text-gray-600 leading-relaxed">
                {{ __('site.projects.list_lead') }}
            </p>
        </div>

        @if ($categories->isNotEmpty())
            <div class="mt-10 flex flex-wrap justify-center gap-2 fade-up">
                <button type="button"
                        @click="active = 'all'"
                        :class="active === 'all'
                            ? 'bg-primary text-white ring-primary'
                            : 'bg-white text-gray-700 ring-gray-200 hover:ring-primary hover:text-primary'"
                        class="rounded-full px-5 py-2 text-sm font-semibold ring-1 transition-colors">
                    {{ __('site.projects.filter_all') }}
                </button>
                @foreach ($categories as $category)
                    <button type="button"
                            @click="active = '{{ $category }}'"
                            :class="active === '{{ $category }}'
                                ? 'bg-primary text-white ring-primary'
                                : 'bg-white text-gray-700 ring-gray-200 hover:ring-primary hover:text-primary'"
                            class="rounded-full px-5 py-2 text-sm font-semibold ring-1 transition-colors">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        @endif

        @if ($projects->isEmpty())
            <div class="mt-16 rounded-2xl bg-white p-12 text-center ring-1 ring-gray-100 fade-up">
                <p class="text-gray-500">{{ __('site.projects.empty') }}</p>
            </div>
        @else
            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    @php $projectCategory = $project->translated('category'); @endphp
                    <article
                        x-show="active === 'all' || active === '{{ $projectCategory }}'"
                        x-transition
                        class="group flex flex-col overflow-hidden rounded-2xl bg-white ring-1 ring-gray-100 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl fade-up">
                        <a href="{{ route('projects.show', $project) }}" class="relative block overflow-hidden">
                            <div class="aspect-[4/3] w-full overflow-hidden bg-offwhite">
                                <img src="{{ $project->imageUrl() }}"
                                     alt="{{ $project->translated('title') }}"
                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            </div>
                            @if ($project->is_featured)
                                <span class="absolute left-4 top-4 rounded-full bg-secondary px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary shadow-sm">
                                    {{ __('site.projects.featured_badge') }}
                                </span>
                            @endif
                            @if ($projectCategory)
                                <span class="absolute right-4 top-4 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                                    {{ $projectCategory }}
                                </span>
                            @endif
                        </a>

                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                @if ($project->location)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.244-4.243a8 8 0 1111.315 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $project->location }}
                                    </span>
                                @endif
                                @if ($project->year)
                                    <span class="text-primary">{{ $project->year }}</span>
                                @endif
                            </div>

                            <h3 class="mt-3 text-lg font-bold text-[#1A1A1A] line-clamp-2">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-primary transition-colors">
                                    {{ $project->translated('title') }}
                                </a>
                            </h3>

                            @if ($project->client)
                                <p class="mt-1 text-sm font-medium text-primary">{{ $project->client }}</p>
                            @endif

                            <p class="mt-3 text-sm text-gray-600 leading-relaxed line-clamp-3">
                                {{ $project->translated('summary') }}
                            </p>

                            <a href="{{ route('projects.show', $project) }}"
                               class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:gap-3 transition-all">
                                {{ __('site.projects.card_cta') }}
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center fade-up">
        <span class="text-sm font-semibold uppercase tracking-wider text-secondary">
            {{ __('site.projects.cta_label') }}
        </span>
        <h2 class="mt-2 text-3xl font-bold text-[#1A1A1A]">{{ __('site.projects.cta_title') }}</h2>
        <p class="mt-4 text-gray-600 leading-relaxed">
            {{ __('site.projects.cta_body') }}
        </p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="mailto:pt.radikabintang@gmail.com"
               class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-primary-dark transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ __('site.projects.cta_email') }}
            </a>
            <a href="https://wa.me/6285330330396" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 rounded-lg bg-[#25D366] px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-[#20BA5A] transition-colors">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 018.413 3.488 11.82 11.82 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zM6.597 20.13c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-.607z"/></svg>
                WhatsApp
            </a>
        </div>
    </div>
</section>

@endsection
