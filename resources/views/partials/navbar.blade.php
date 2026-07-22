@php
    $navLinks = [
        ['label' => __('site.nav.home'), 'route' => 'home'],
        ['label' => __('site.nav.catalog'), 'route' => 'catalog.index'],
        ['label' => __('site.nav.about'), 'route' => 'about'],
    ];
@endphp

<header
    x-data="{ open: false, scrolled: false }"
    x-init="scrolled = window.scrollY > 10; window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
    class="sticky top-0 z-50 bg-white border-b-2 border-primary transition-shadow duration-300"
    :class="scrolled ? 'shadow-md' : ''"
>
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary text-white font-extrabold text-lg shadow-sm">
                    RBN
                </div>
                <div class="leading-tight">
                    <span class="block font-bold text-primary text-sm sm:text-base">PT. Radika Bintang Nusantara</span>
                    <span class="hidden sm:inline-flex items-center gap-1 mt-0.5 rounded-full bg-secondary/15 px-2 py-0.5 text-[10px] font-semibold text-primary">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.3 3.29 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                        {{ __('site.nav.authorized_partner') }}
                    </span>
                </div>
            </a>

            {{-- Desktop menu --}}
            <div class="hidden md:flex items-center gap-6">
                @foreach ($navLinks as $link)
                    @php $active = request()->routeIs($link['route']) || ($link['route'] === 'catalog.index' && request()->routeIs('catalog.*')); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="relative text-sm font-medium transition-colors {{ $active ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                        {{ $link['label'] }}
                        <span class="absolute -bottom-1 left-0 h-0.5 bg-secondary transition-all {{ $active ? 'w-full' : 'w-0' }}"></span>
                    </a>
                @endforeach

                @include('partials.language-switcher')

                <a href="mailto:pt.radikabintang@gmail.com"
                   class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-primary-dark">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('site.nav.contact') }}
                </a>
            </div>

            {{-- Mobile: switcher + hamburger --}}
            <div class="md:hidden flex items-center gap-2">
                @include('partials.language-switcher')
                <button @click="open = !open" class="inline-flex items-center justify-center rounded-md p-2 text-primary hover:bg-cream" aria-label="Toggle menu">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-cloak x-transition class="md:hidden pb-4">
            <div class="flex flex-col gap-1">
                @foreach ($navLinks as $link)
                    @php $active = request()->routeIs($link['route']) || ($link['route'] === 'catalog.index' && request()->routeIs('catalog.*')); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="rounded-md px-3 py-2 text-base font-medium {{ $active ? 'bg-cream text-primary' : 'text-gray-700 hover:bg-cream hover:text-primary' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a href="mailto:pt.radikabintang@gmail.com"
                   class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
                    {{ __('site.nav.contact') }}
                </a>
            </div>
        </div>
    </nav>
</header>
