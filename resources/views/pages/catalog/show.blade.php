@extends('layouts.app')

@section('title', $product->translated('name'))
@section('meta_description', Str::limit(strip_tags($product->translated('short_description') ?? ''), 155))

@section('content')

@php
    $waText = rawurlencode(__('site.product.wa_greeting') . ' ' . $product->translated('name'));
    $waLink = 'https://wa.me/6285330330396?text=' . $waText;
    $mailLink = 'mailto:pt.radikabintang@gmail.com?subject=' . rawurlencode('Penawaran Produk: ' . $product->translated('name'));

    $specs = $product->translated('specifications');
    $features = $product->translated('features');
@endphp

{{-- Breadcrumb --}}
<section class="bg-white border-b border-gray-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-primary">{{ __('site.product.breadcrumb_home') }}</a>
            <span>/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-primary">{{ __('site.product.breadcrumb_catalog') }}</a>
            @if ($product->category)
                <span>/</span>
                <a href="{{ route('catalog.index', ['category' => $product->category->id]) }}" class="hover:text-primary">{{ $product->category->translated('name') }}</a>
            @endif
            <span>/</span>
            <span class="font-medium text-gray-800">{{ $product->translated('name') }}</span>
        </nav>
    </div>
</section>

<section class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
            @php
                $gallery = $product->images->map(fn ($img) => ['url' => $img->url()])->values()->all();
                if (empty($gallery)) {
                    $gallery = [['url' => $product->imageUrl()]];
                }
            @endphp
            <div x-data="{ active: 0, mainLoaded: false, images: {{ Illuminate\Support\Js::from($gallery) }} }">
                <div class="relative aspect-[3/2] overflow-hidden rounded-2xl bg-cream shadow-md ring-1 ring-gray-100">
                    <div x-show="!mainLoaded" class="absolute inset-0 skeleton" aria-hidden="true"></div>
                    <template x-for="(img, i) in images" :key="i">
                        <img :src="img.url" :alt="'{{ addslashes($product->translated('name')) }} - ' + (i+1)"
                             class="absolute inset-0 h-full w-full object-cover img-fade"
                             :class="mainLoaded ? 'is-loaded' : ''"
                             x-show="active === i"
                             x-transition.opacity
                             @load="if (active === i) mainLoaded = true"
                             x-init="if ($el.complete && active === i) mainLoaded = true">
                    </template>
                </div>

                @if (count($gallery) > 1)
                    <div class="mt-4 grid grid-cols-4 gap-3 sm:grid-cols-5">
                        <template x-for="(img, i) in images" :key="i">
                            <button type="button" @click="active = i"
                                    class="group relative overflow-hidden rounded-lg ring-2 transition-all"
                                    :class="active === i ? 'ring-primary' : 'ring-transparent hover:ring-secondary/50'">
                                <img :src="img.url" alt="" class="aspect-square w-full object-cover"
                                     :class="active === i ? '' : 'opacity-70 group-hover:opacity-100'">
                            </button>
                        </template>
                    </div>
                @endif
            </div>

            <div>
                @if ($product->category)
                    <span class="inline-flex rounded-full bg-secondary/15 px-3 py-1 text-xs font-semibold text-primary">{{ $product->category->translated('name') }}</span>
                @endif
                <h1 class="mt-4 text-3xl font-extrabold text-[#1A1A1A] sm:text-4xl">{{ $product->translated('name') }}</h1>
                <div class="mt-4 h-1 w-20 rounded bg-secondary"></div>
                <p class="mt-5 text-gray-600 leading-relaxed">{{ $product->translated('short_description') }}</p>

                @if (!empty($specs) && is_array($specs))
                    <div class="mt-7">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ __('site.product.specifications') }}</h3>
                        <table class="mt-3 w-full overflow-hidden rounded-xl text-sm ring-1 ring-gray-100">
                            <tbody>
                                @foreach ($specs as $key => $value)
                                    <tr class="{{ $loop->even ? 'bg-white' : 'bg-cream' }}">
                                        <td class="w-2/5 px-4 py-3 font-medium text-gray-700">{{ $key }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $mailLink }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-6 py-3.5 text-sm font-semibold text-white transition-colors hover:bg-primary-dark">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ __('site.product.cta_email') }}
                    </a>
                    <a href="{{ $waLink }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-secondary px-6 py-3.5 text-sm font-semibold text-primary transition-colors hover:bg-secondary hover:text-white">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 018.413 3.488 11.82 11.82 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zM6.597 20.13c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-.607zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        {{ __('site.product.cta_whatsapp') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-14" x-data="{ tab: 'desc' }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex gap-2 border-b border-gray-200">
            <button @click="tab = 'desc'" :class="tab === 'desc' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-primary'"
                    class="-mb-px border-b-2 px-5 py-3 text-sm font-semibold transition-colors">{{ __('site.product.tab_description') }}</button>
            <button @click="tab = 'features'" :class="tab === 'features' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-primary'"
                    class="-mb-px border-b-2 px-5 py-3 text-sm font-semibold transition-colors">{{ __('site.product.tab_features') }}</button>
        </div>

        <div class="mt-8">
            <div x-show="tab === 'desc'" class="prose max-w-none text-gray-600 leading-relaxed [&_p]:mb-4 [&_strong]:text-gray-800">
                {!! $product->formattedDescription() !!}
            </div>

            <div x-show="tab === 'features'" x-cloak>
                @if (!empty($features) && is_array($features))
                    <ul class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        @foreach ($features as $feature)
                            <li class="flex items-start gap-3 rounded-xl bg-offwhite p-4 ring-1 ring-gray-100">
                                <span class="mt-0.5 flex h-6 w-6 flex-none items-center justify-center rounded-full bg-secondary/15 text-primary">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.3 3.29 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                </span>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">{{ __('site.product.features_empty') }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

@if ($related->isNotEmpty())
<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-[#1A1A1A] sm:text-3xl">{{ __('site.product.related_title') }}</h2>
        <div class="mt-8 grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($related as $item)
                @include('partials.product-card', ['product' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
