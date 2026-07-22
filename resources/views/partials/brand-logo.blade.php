{{--
    Radika brand mark (star icon).
    Params:
      - $size      (sm|md|lg|xl) default md
      - $wrapClass extra classes for outer wrapper
      - $wrapBg    background class for the container (default bg-white)
--}}
@php
    $size = $size ?? 'md';
    $wrapClass = $wrapClass ?? '';
    $wrapBg = $wrapBg ?? 'bg-white';

    $sizes = [
        'sm' => 'h-9 w-9 rounded-md',
        'md' => 'h-11 w-11 rounded-lg',
        'lg' => 'h-14 w-14 rounded-xl',
        'xl' => 'h-20 w-20 rounded-2xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];

    // Prefer a user-uploaded raster (public/images/logo.png). Fall back to the
    // bundled SVG mark that ships with the project.
    $png = public_path('images/logo.png');
    $logoUrl = file_exists($png) ? asset('images/logo.png') : asset('images/logo.svg');
@endphp

<div class="flex flex-none items-center justify-center overflow-hidden shadow-sm ring-1 ring-black/5 {{ $wrapBg }} {{ $sizeClass }} {{ $wrapClass }}">
    <img src="{{ $logoUrl }}" alt="PT. Radika Bintang Nusantara" class="h-full w-full object-contain p-1">
</div>
