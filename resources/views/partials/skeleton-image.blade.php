{{--
    Skeleton-wrapped image with shimmer while loading + fade-in when ready.
    Params:
      - $src        (required) — image URL
      - $alt        (optional) — alt text
      - $imgClass   (optional) — extra classes for <img>
      - $wrapClass  (optional) — extra classes for wrapper (aspect + rounded etc)
      - $lazy       (optional, default true)
--}}
@php
    $alt = $alt ?? '';
    $imgClass = $imgClass ?? 'h-full w-full object-cover';
    $wrapClass = $wrapClass ?? 'relative overflow-hidden bg-cream';
    $lazy = $lazy ?? true;
@endphp

<div class="{{ $wrapClass }}" x-data="{ loaded: false }" x-init="if ($refs.img.complete && $refs.img.naturalWidth > 0) loaded = true">
    <div x-show="!loaded" class="absolute inset-0 skeleton" aria-hidden="true"></div>
    <img x-ref="img"
         src="{{ $src }}" alt="{{ $alt }}"
         @if ($lazy) loading="lazy" @endif
         x-on:load="loaded = true"
         x-on:error="loaded = true"
         :class="loaded ? 'is-loaded' : ''"
         class="{{ $imgClass }} img-fade relative">
</div>
