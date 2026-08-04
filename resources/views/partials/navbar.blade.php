@php
    $navLinks = [
        ['label' => __('site.nav.home'), 'route' => 'home'],
        ['label' => __('site.nav.catalog'), 'route' => 'catalog.index'],
        ['label' => __('site.nav.projects'), 'route' => 'projects.index'],
        ['label' => __('site.nav.about'), 'route' => 'about'],
    ];
    // Pages that don't start with a dark hero OR use sticky stacked
    // sections — force the header to its solid state so the white nav
    // text stays readable throughout the scroll.
    $forceSolidNav = request()->routeIs('catalog.show')
        || request()->routeIs('projects.index');
@endphp

<header
    x-data="{ open: false, scrolled: false, forceSolid: @json($forceSolidNav) }"
    x-init="
        scrolled = forceSolid || window.scrollY > 80;
        window.addEventListener('scroll', () => scrolled = forceSolid || window.scrollY > 80)
    "
    class="sticky top-0 z-50 transition-all duration-300"
    :class="scrolled || open
        ? 'bg-primary shadow-lg'
        : 'bg-transparent'"
>
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @include('partials.brand-logo', ['size' => 'md'])
                <div class="leading-tight">
                    <span class="block font-bold text-sm sm:text-base text-white">
                        PT. Radika Bintang Nusantara
                    </span>
                    <span class="hidden sm:inline-flex items-center gap-1 mt-0.5 rounded-full px-2 py-0.5 text-[10px] font-semibold bg-white/15 text-white ring-1 ring-white/25">
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
                       class="relative text-sm font-medium transition-colors {{ $active ? 'text-white' : 'text-white/80 hover:text-white' }}">
                        {{ $link['label'] }}
                        <span class="absolute -bottom-1 left-0 h-0.5 bg-secondary transition-all {{ $active ? 'w-full' : 'w-0' }}"></span>
                    </a>
                @endforeach

                @include('partials.language-switcher')

                <a href="https://wa.me/6285330330396" target="_blank" rel="noopener"
                   class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#25D366] text-white shadow-sm transition-all hover:bg-[#20BA5A] hover:scale-105"
                   aria-label="WhatsApp">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 018.413 3.488 11.82 11.82 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zM6.597 20.13c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-.607zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                </a>

                <a href="mailto:pt.radikabintang@gmail.com"
                   class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/25 transition-colors hover:bg-white/25">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('site.nav.contact') }}
                </a>
            </div>

            {{-- Mobile: switcher + hamburger --}}
            <div class="md:hidden flex items-center gap-2">
                @include('partials.language-switcher')
                <button @click="open = !open"
                        class="inline-flex items-center justify-center rounded-md p-2 text-white transition-colors hover:bg-white/10"
                        aria-label="Toggle menu">
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
                       class="rounded-md px-3 py-2 text-base font-medium {{ $active ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a href="mailto:pt.radikabintang@gmail.com"
                   class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-white/15 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/25 hover:bg-white/25">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('site.nav.contact') }}
                </a>
                <a href="https://wa.me/6285330330396" target="_blank" rel="noopener"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#25D366] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#20BA5A]">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 018.413 3.488 11.82 11.82 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zM6.597 20.13c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-.607zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </nav>
</header>
